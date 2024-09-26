<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Signature;

class CertificateRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    protected $fillable = [
        'learner_id',
        'course_id',
        'approved_by',
        'approved_at',
        'approval_sign_id',
        'authorized_by',
        'authorized_at',
        'authorization_sign_id',
        'state'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'approved_at',
        'authorized_at',


    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function learner()
    {
        return $this->belongsTo(Identity::class)->select('id', 'first_name', 'last_name');
    }
    public function approvedBy()
    {
        return $this->belongsTo(Identity::class, "approved_by")->select('id', 'first_name', 'last_name');
    }
    public function authorizedBy()
    {
        return $this->belongsTo(Identity::class, "authorized_by")->select('id', 'first_name', 'last_name');
    }

    public function approvalSignature()
    {
        return $this->belongsTo(Signature::class, 'approval_sign_id');
    }
    public function authSignature()
    {
        return $this->belongsTo(Signature::class, 'approval_sign_id');
    }
    public static function getAll($courseId, $state = 'new')
    {
        $state = request()->query('state');  // Get 'state' from query parameters
        $query = self::query();
        if ($state) {
            $query->where('state', $state);
        }
        $query->with('course', 'learner', 'approvedBy', 'authorizedBy', 'authSignature', 'approvalSignature');
        return $query->get();
    }

    public static function getOne($iid)
    {
        return self::with('course', 'learner')->find($iid);
    }
    public static function register($data)
    {
        $data['learner_id'] = Auth()->user()->identity_id;
        $request = self::create($data);
        return self::getOne($request->id);
    }
    public static function updateRequest($data, $iid)
    {
        $examRequest = self::find($iid);
        $examRequest->update($data);
        return self::getOne($examRequest->id);
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
            $request['approved_at'] = $time;
            $request['approved_by'] = $userId;
            $request['approval_sign'] = $sign->id;
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
        $data['learner_id'] = $userId;
        $sign = Signature::where('identity_id', $userId)->first();
        $requests = self::whereIn('id', $iids)->get();
        foreach ($requests as $request) {
            $request->update(['state' => 'approved', 'approved_at' => $time, 'approved_by' => $userId, 'approval_sign_id' => $sign->id]);
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
            $request->update(['state' => 'rejected', 'approved_at' => $time, 'approved_by' => $userId, 'approval_sign_id' => $sign->id]);
        }
        return $requests;
    }
    public static function authorizeRequest($iids)
    {
        $time = Carbon::now();
        $userId = Auth()->user()->identity_id;
        $sign = Signature::where('identity_id', $userId)->first();
        $authorizedRequests = [];
        $requests = self::whereIn('id', $iids)->get();
        foreach ($requests as $request) {
            if ($request->state == 'approved') {
                $request->update(['state' => 'authorized', 'authorized_at' => $time, 'authorized_by' => $userId,  'authorization_sign_id' => $sign->id]);
                array_push($authorizedRequests, $request);
            }
        }

        return $authorizedRequests;
    }
}
