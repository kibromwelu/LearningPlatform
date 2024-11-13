<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCreatorRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'biography',
        'resume_id',
        'course_name',
        'description',
        'state',
        'clo_action',
        'ceo_action',
        'clo_action_date',
        'ceo_action_date',
        'clo_id',
        'ceo_id',
    ];

    public function user()
    {
        return $this->belongsTo(Identity::class)->select('id', 'first_name', 'last_name');;
    }
    public function clo()
    {
        return $this->belongsTo(Identity::class, 'clo_id')->select('id', 'first_name', 'last_name');;
    }
    public function ceo()
    {
        return $this->belongsTo(Identity::class, 'ceo_id')->select('id', 'first_name', 'last_name');
    }


    public static function store($data)
    {
        $data['user_id'] = Auth()->user()->identity_id;
        return self::create($data);
    }
    public static function getOne($id)
    {
        return self::with('user', 'ceo', 'clo')->find($id);
    }
    public static function updateRequest($data, $id)
    {
        // $data['user_id'] = Auth()->user()->identity_id;
        $request = self::findOrFail($id);
        $request->update($data);
        return $request;
    }
    public static function getAll()
    {
        $state = request()->query('state') ?? 'new';  // Get 'state' from query parameters
        $query = self::query();
        if ($state) {
            $query->where('state', $state);
        }

        $query->with('user', 'ceo', 'clo');
        $requests = $query->get();
        return $requests;
    }
    public static function  destroy($id)
    {
        $request = self::findOrFail($id);
        $request->delete();
        return;
    }
    public static function changeState($data)
    {
        $userId = Auth()->user()->identity_id;
        $now = Carbon::now();
        foreach ($data['requestIds'] as $requestId) {

            $request = self::findOrFail($requestId);

            if ($data['state'] == 'approve') {
                $request->update(['state' => 'approved', 'clo_action_date' => $now, 'clo_action' => 'approve', 'clo_id' => $userId]);
                return 'Course Creators request approved';
            } else if ($data['state'] == 'authorize') {
                $request->update(['state' => 'authorized', 'ceo_action_date' => $now, 'ceo_action' => 'authorize', 'ceo_id' => $userId]);
                return 'Course Creators request authrized';
            } else if ($data['state'] == 'reject' && $request->clo_id != null) {
                $request->update(['state' => 'ceo-rejected', 'ceo_action_date' => $now, 'ceo_action' => 'reject', 'ceo_id' => $userId]);
                return 'Course Creators request rejected';
            } else if ($data['state'] == 'reject' && $request->clo_id == null) {
                $request->update(['state' => 'clo-rejected', 'clo_action_date' => $now, 'clo_action' => 'reject', 'clo_id' => $userId]);
                return 'Course Creators request rejected';
            }
        }
    }
}
