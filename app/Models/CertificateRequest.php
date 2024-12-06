<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Signature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificateRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    protected $fillable = [
        'enrollment_id',
        'clo_id',
        'clo_action',
        'clo_action_date',
        'clo_sign_id',
        'ceo_id',
        'ceo_action',
        'ceo_action_date',
        'ceo_sign_id',
        'state'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        // 'clo_action_date',
        // 'ceo_action_date',


    ];

    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class);
    }
    public function learner()
    {
        return $this->belongsTo(Identity::class)->select('id', 'first_name', 'last_name');
    }
    public function approvedBy()
    {
        return $this->belongsTo(Identity::class, "clo_id")->select('id', 'first_name', 'last_name');
    }
    public function authorizedBy()
    {
        return $this->belongsTo(Identity::class, "ceo_id")->select('id', 'first_name', 'last_name');
    }

    public function approvalSignature()
    {
        return $this->belongsTo(Signature::class, 'clo_sign_id');
    }
    public function authSignature()
    {
        return $this->belongsTo(Signature::class, 'ceo_sign_id');
    }
    public static function getAll($state = null)
    {
        $state = request()->query('state');  // Get 'state' from query parameters
        $query = self::query();
        if ($state) {
            $query->where('state', $state);
        }

        $query->with('enrollment.course', 'enrollment.learner.identity', 'approvedBy', 'authorizedBy', 'authSignature', 'approvalSignature');
        $requests = $query->get();

        foreach ($requests as $request) {
            $request->CLO_Sign = $request->approvalSignature ? url('/api/auth/sign/') . '/' . $request->approvalSignature->filename : null;
            $request->score = self::calculateLearnerResult($request->enrollment_id, $request->enrollment->course_id);
            $request->CEO_Sign = $request->authSignature ?  url('/api/auth/sign/') . '/' . $request->authSignature->filename : null;
        }
        return $requests;
    }
    static function calculateLearnerResult($enrollmentId, $courseId)
    {
        //
        $totalQuestionsToAsk = Topic::whereHas('module', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
            ->sum('number_of_questions_to_ask');
        //get total quiz score
        $quizScores = AssessmentAttempt::where('enrollment_id', $enrollmentId)
            ->where('type', 'quiz')
            ->whereHas('topic.module', function ($query) use ($courseId) {
                $query->where(
                    'course_id',
                    $courseId
                );
            })
            ->select('topic_id', DB::raw('MAX(score) as max_score'))
            ->groupBy('topic_id')
            ->pluck('max_score');
        $totalQuizScore = $quizScores->sum();

        // calculate quiz result out of 60
        $quizScore = round(($totalQuizScore / $totalQuestionsToAsk) * 60, 2);
        //fetch max final exam result
        $maxFinalExamScore = AssessmentAttempt::where('enrollment_id', $enrollmentId)
            ->where('type', 'final')
            ->max('score');

        $totalScore = $quizScore + ($maxFinalExamScore ?? 0);

        return $totalScore;
    }

    public static function getOne($iid)
    {
        $request = self::with('enrollment.course', 'enrollment.learner', 'approvedBy', 'authorizedBy', 'authSignature', 'approvalSignature')->findOrFail($iid);
        $request->approvalSign = $request->approvalSignature ? url('/api/auth/sign/') . '/' . $request->approvalSignature->filename : null;
        return $request;
    }
    public static function register($data)
    {
        // $data['learner_id'] = Auth()->user()->identity_id;
        // dd($data);
        $request = self::create($data);
        return $request;
    }

    public static function updateRequest($data)
    {
       

        $user = Auth()->user();
        $time = Carbon::now();
        $sign = Signature::where('identity_id', $user->identity_id)->where('state', 'active')->first();

        Log::info($data['requestIds']);
        $certificateRequests = self::whereIn('id', $data['requestIds'])->get();
        $state = $data['state'];
        foreach ($certificateRequests as $request) {
            $enrollment = CourseEnrollment::findOrFail($request->enrollment_id);
            $enrollment->update(['state' => $state == 'ceo-reject' || $state == 'clo-reject'? 'pending' : ($state == 'authorize'? 'certified':'certificate_requested')]);
            if ($data['state'] == 'approve') {
                $request->update(['state' => 'approved', 'clo_id' => $user->identity_id, 'clo_action' => 'approve', 'clo_action_date' => $time, 'clo_sign_id' => $sign->id]);
            } elseif ($data['state'] == 'authorize') {
                $request->update(['state' => 'authorized', 'ceo_id' => $user->identity_id, 'ceo_action' => 'authorize', 'ceo_action_date' => $time, 'ceo_sign_id' => $sign->id]);
            } elseif ($data['state'] == 'ceo-reject') {
                $request->update(['state' => 'ceo-rejected', 'ceo_id' => $user->identity_id, 'ceo_action' => 'reject', 'ceo_action_date' => $time, 'ceo_sign_id' => $sign->id]);
            } elseif ($data['state'] == 'clo-reject') {
                $request->update(['state' => 'clo-rejected', 'clo_id' => $user->identity_id, 'clo_action' => 'reject', 'clo_action_date' => $time, 'clo_sign_id' => $sign->id]);
            }
        }
        return $certificateRequests;
    }
    public static function deleteRequest($iid)
    {
        $examRequest = self::find($iid);
        return $examRequest->delete();
    }
    public static function groupRequests($certificateRequests)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        $sign = Signature::where('identity_id', $userId)->first();
        $createdRequests = [];
        foreach ($certificateRequests as $request) {
            $request['clo_action_date'] = $time;
            $request['clo_id'] = $userId;
            $request['cli_sign_id'] = $sign->id;
            $request['state'] = 'approved';
            $req =  self::create($request);
            array_push($createdRequests, $req);
        }
        return $createdRequests;
    }
    public static function approveRequest($iids)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        // $data['learner_id'] = $userId;
        $sign = Signature::where('identity_id', $userId)->where('state', 'active')->first();
        $requests = self::whereIn('id', $iids)->get();

        foreach ($requests as $request) {
            $request->update(['state' => 'approved', 'clo_action_date' => $time, 'clo_id' => $userId, 'clo_sign_id' => $sign->id, 'clo_action' => 'approve']);
        }
        return $requests;
    }
    public static function undoReject($iids)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        $sign = Signature::where('identity_id', $userId)->where('state', 'active')->first();
        $requests = self::whereIn('id', $iids)->get();

        foreach ($requests as $request) {
            $state = 'new';
            if ($request->clo_id) {
                $state = 'approved';
            }
            $request->update(['state' => $state, 'clo_action_date' => $time, 'clo_id' => $userId, 'clo_sign_id' => $sign->id, 'clo_action' => 'approve']);
        }
        return $requests;
    }
    public static function rejectRequest($iids)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        $data['learner_id'] = $userId;
        $sign = Signature::where('identity_id', $userId)->first();
        $requests = self::whereIn('id', $iids)->get();
        foreach ($requests as $request) {
            $request->update(['state' => 'CLO-rejected', 'clo_action_date' => $time, 'clo_id' => $userId, 'clo_sign_id' => $sign->id]);
        }
        return $requests;
    }
    public static function rejectApprovedRequest($iids)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        $data['learner_id'] = $userId;
        $sign = Signature::where('identity_id', $userId)->first();
        $requests = self::whereIn('id', $iids)->get();
        foreach ($requests as $request) {
            $request->update(['state' => 'CEO-rejected', 'ceo_action_date' => $time, 'ceo_id' => $userId, 'ceo_sign_id' => $sign->id]);
        }
        return $requests;
    }
    public static function authorizeRequest($iids)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        $sign = Signature::where('identity_id', $userId)->where('state', 'active')->first();
        $authorizedRequests = [];
        $requests = self::whereIn('id', $iids)->get();
        foreach ($requests as $request) {
            if ($request->state == 'approved') {
                $request->update(['state' => 'authorized', 'ceo_action_date' => $time, 'ceo_id' => $userId,  'ceo_sign_id' => $sign->id, 'ceo_action' => 'authorize']);
                array_push($authorizedRequests, $request);
            }
        }

        return $authorizedRequests;
    }
}
