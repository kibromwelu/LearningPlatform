<?php

namespace App\Services;

use Carbon\Carbon;

class TimeService
{

    public static function timeAgo($records)
    {
        foreach ($records as $record) {
            if (isset($record->created_at)) {
                $record->time_ago = Carbon::parse($record->created_at)->diffForHumans();
            }
        }
        return $records;
    }
}
