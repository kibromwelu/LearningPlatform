<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getMyActivity()
    {
        $response = ActivityLog::getMyActivity();
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
    public function destroy(ActivityLog $activityLog) {}
}
