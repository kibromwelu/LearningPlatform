<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ActivityLog extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'identity_id',
        'action_type',
        'content_id',
        'content_type',
        'remark',
    ];

    public function identity()
    {
        return $this->belongsTo(Identity::class)->select('id', 'first_name', 'last_name');
    }
    public function content()
    {
        return $this->morphTo();
    }

    public static function store($data)
    {
        return self::create($data);
    }

    public static function getMyActivity()
    {


        $user =  Auth()->user()->identity_id;

        $currentDate = Carbon::now();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $todayActivities = self::where('identity_id', $user)->whereDate('created_at', $currentDate->toDateString())
            ->with('identity', 'identity.profile', 'content')->select('id', 'identity_id', 'action_type', 'content_id', 'content_type', 'remark', 'created_at')->orderBy('created_at', 'desc')
            ->get()->map(function ($log) {
                return [
                    'id' => $log->id,
                    'identity_id' => $log->identity_id,
                    'identity' => $log->identity,
                    'action_type' => $log->action_type,
                    'content_id' => $log->content_id,
                    'content' => $log->content,
                    'remark' => $log->remark,
                    'created_at' => $log->created_at

                ];
            });
        $thisWeekActivities = self::where('identity_id', $user)->where('created_at', '>=', $startOfWeek)
            ->where('created_at', '<', $endOfWeek)
            ->with('identity', 'identity.profile', 'content')->select('id', 'identity_id', 'action_type', 'content_id', 'content_type', 'remark', 'created_at')->orderBy('created_at', 'desc')
            ->get()->map(function ($log) {
                return [
                    'id' => $log->id,
                    'identity_id' => $log->identity_id,
                    'identity' => $log->identity,
                    'action_type' => $log->action_type,
                    'content_id' => $log->content_id,
                    'content' => $log->content,
                    'remark' => $log->remark,
                    'created_at' => $log->created_at
                ];
            });
        $earlierActivities = self::where('identity_id', $user)->where('created_at', '<', Carbon::now()->subDays(7))
            ->with('identity', 'identity.profile', 'content')->select('id', 'identity_id', 'action_type', 'content_id', 'content_type', 'remark', 'created_at')->orderBy('created_at', 'desc')
            ->get()->map(function ($log) {
                return [
                    'id' => $log->id,
                    'identity_id' => $log->identity_id,
                    'identity' => $log->identity,
                    'action_type' => $log->action_type,
                    'content_id' => $log->content_id,
                    'content' => $log->content,
                    'remark' => $log->remark,
                    'created_at' => $log->created_at
                ];
            });
        $activityLogs = [
            'today_activities' => $todayActivities,
            'this_week_activities' => $thisWeekActivities,
            'earlier_activities' => $earlierActivities,
        ];
        return $activityLogs;
    }
}
