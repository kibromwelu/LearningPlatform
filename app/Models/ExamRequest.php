<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'state',
        'clo_id',
        'clo_action_date',
        'clo_action',
        'ceo_id',
        'ceo_action_date',
        'ceo_action',
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }


    public function approvedBy()
    {
        return $this->belongsTo(Identity::class, "clo_id")->select('id', 'first_name', 'last_name');
    }
    public static function getAll()
    {
        $state = request()->query('state');
        $query = self::query();
        if ($state) {
            $query->where('state', $state);
        }

        $query->with('enrollment.course', 'enrollment.learner.identity', 'approvedBy');
        $requests = $query->get();
        foreach ($requests as $request) {
            $request->learning_progress = self::calculateLearningProgress($request->enrollment->course_id, $request->enrollment_id) . ' %';
        }
        return $requests;
    }
    public static function registerExamRequest($data)
    {
        return self::create($data);
    }
    static function calculateLearningProgress($courseId, $enrollmentId)
    {
        $topicCount =  Topic::whereHas('module', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->count();
        $completedTopics = LearnerProgress::where('enrollment_id', $enrollmentId)->count();
        if ($topicCount > 0) {
            return ($completedTopics / $topicCount) * 100;
        } else {

            return 0;
        }
    }
    public static function updateExamRequest($data)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        $requests = self::whereIn('id', $data['requestIds'])->get();
        foreach ($requests as $request) {
            if ($data['state'] == 'approve') {
                $formattedData['state'] = 'approved';
                $formattedData['clo_action'] = 'approve';
                $formattedData['clo_action_date'] = $time;
                $formattedData['clo_id'] = $userId;
            } else if ($data['state'] == 'authorize') {
                $formattedData['state'] = 'authorized';
                $formattedData['ceo_action'] = 'authorize';
                $formattedData['ceo_action_date'] = $time;
                $formattedData['ceo_id'] = $userId;
            } else if ($data['state'] == 'ceo-reject') {
                $formattedData['state'] = 'ceo-rejected';
                $formattedData['ceo_action'] = 'reject';
                $formattedData['ceo_action_date'] = $time;
                $formattedData['ceo_id'] = $userId;
            } else if ($data['state'] == 'clo-reject') {
                $formattedData['state'] = 'clo-rejected';
                $formattedData['ceo_action'] = 'reject';
                $formattedData['ceo_action_date'] = $time;
                $formattedData['ceo_id'] = $userId;
            }
            $request->update($formattedData);
        }
        return $requests;
    }
    public static function deleteRequest($id)
    {
        $request = self::findOrFail($id);
        return $request->delete();
    }
    public static function undoReject($iids)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        // $sign = Signature::where('identity_id', $userId)->where('state', 'active')->first();
        $requests = self::whereIn('id', $iids)->get();

        foreach ($requests as $request) {
            $state = 'new';
            $action = 'undo reject';
            if ($request->clo_id) {
                $state = 'approved';
                $action = 'approve';
            }
            $request->update(['state' => $state, 'clo_action_date' => $time, 'clo_id' => $userId,  'clo_action' => $action]);
        }
        return $requests;
    }
    public static function approveRequest($id)
    {
        $data['state'] = 'approved';
        $data['approved_at'] = Carbon::now();
        $data['approved_by'] = Auth()->user()->identity_id;
        return self::updateExamRequest($data, $id);
    }
    public static function authorizeExamRequest($id)
    {
        $examRequest = self::find($id);
        if ($examRequest->state == 'approved') {

            $data['state'] = 'authorized';
            $data['authorized_at'] = Carbon::now();
            $data['authorized_by'] = Auth()->user()->identity_id;
            // dd($data);
            return self::updateExamRequest($data, $id);
        }
        throw new \Exception('It should be approved first', 400);
    }
}
