<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PublishRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'course_id',
        'requested_by',
        'request_action',
        'ceo_action',
        'ceo_id',
        'ceo_action_date',
        'remark',
        'state'
    ];

    public function ceo()
    {
        return $this->belongsTo(Identity::class, 'ceo_id')->select('id', 'first_name', 'last_name');
    }
    public function requestedBy()
    {
        return $this->belongsTo(Identity::class, 'requested_by')->select('id', 'first_name', 'last_name');
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public static function getRejectedPublishRemark($courseId)
    {
        return self::where('course_id', $courseId)->orderBy('updated_at', 'desc')->first();
    }

    public static function store($data)
    {
        // foreach($data['course_id'])
        $data['requested_by'] = Auth()->user()->identity_id;
        DB::beginTransaction();
        try {
            $request = self::create($data);
            $newData['state'] = 'publish-requested';
            Course::updateCourse($newData, $data['course_id']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Something went wrong ' . $th);
        }

        return $request;
    }
    public static function updateRequest($data)
    {
        $user = Auth()->user();
        $time = Carbon::now();
        $publishRequests = self::whereIn('id', $data['requestIds'])->get();
        DB::beginTransaction();
        foreach ($publishRequests as $request) {
            $courseId = $request->course_id;
            // dd($courseId);
            $newData = [];

            if ($data['state'] == 'authorize') {
                // dd($time);
                $request->update(['state' => 'authorized', 'ceo_id' => $user->identity_id, 'ceo_action' => 'authorize', 'clo_action_date' => $time]);
                $newData['state'] = 'published';
                Course::updateCourse($newData, $courseId);


                // return 'Authorized';
            } elseif ($data['state'] == 'approve') {
                $request->update(['state' => 'published', 'ceo_id' => $user->identity_id, 'ceo_action' => 'publish', 'ceo_action_date' => $time]);
                $newData['state'] = 'publish-requested';

                Course::updateCourse($newData, $courseId);


                // return "Published";
            } elseif ($data['state'] == 'reject') {
                $request->update(['state' => 'rejected', 'remark' => $data['remark'], 'ceo_id' => $user->identity_id, 'ceo_action' => 'publish', 'ceo_action_date' => $time]);
                // return "Rejected";
                // $course->update(['state' => 'rejected']);publish-requested
                $newData['state'] = 'rejected';
                Course::updateCourse($newData, $courseId);
            }
        }
        DB::commit();
        return $data['state'] == 'authorize' ? 'Authorized' : ($data['state'] == 'publish' ? 'Published' : 'Rejected');
    }
    public static function getAll()
    {
        $state = request()->query('state') ?? 'new';
        $query = self::query();
        $query->where('state', $state);
        $query->with('ceo', 'course', 'requestedBy');
        return $query->get();
    }
    public static function show($id)
    {
        return self::with('ceo', 'course', 'requestedBy')->find($id);
    }
    public static function destroy($id)
    {
        $request = self::findOrFail($id);
        return $request->delete();
        // return self::with('ceo', 'course', 'requestedBy')->find($id);
    }
}
