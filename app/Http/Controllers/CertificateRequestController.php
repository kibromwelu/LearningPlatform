<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Http\Requests\StoreCertificateRequestRequest;
use App\Http\Requests\UpdateCertificateRequestRequest;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $response = CertificateRequest::getAll($request->state);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificateRequestRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $response = CertificateRequest::register($request->validated());
            if ($request->state && $request->state == 'approve')
                CertificateRequest::updateRequest([
                    'state' => $request->state,
                    'requestIds' => [$response->id]
                ]);
            CourseEnrollment::findOrFail($request->enrollment_id)->update(['state' => 'certificate_requested']);
            return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 201);
        });
    }


    public function show($id)
    {
        //
        $response = CertificateRequest::getOne($id);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }


    public function undoReject(Request $request)
    {
        $response =  CertificateRequest::undoReject($request->requestIds);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificateRequestRequest $request)
    {
        //
        $response = CertificateRequest::updateRequest($request->validated());
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }

    public function destroy($id)
    {
        $response = CertificateRequest::deleteRequest($id);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
    public static function approveRequest(Request $request)
    {
        // dd($request->requestIds);
        $response = CertificateRequest::approveRequest($request->requestIds);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 201);
    }
    public static function rejectRequest(Request $request)
    {
        // dd($request->requestIds);
        $response = CertificateRequest::rejectRequest($request->requestIds);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
    public static function rejectApprovedRequest(Request $request)
    {
        // dd($request->requestIds);
        $response = CertificateRequest::rejectApprovedRequest($request->requestIds);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
    public static function authorizeRequest(Request $requestIds)
    {
        $response = CertificateRequest::authorizeRequest($requestIds);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
    public static function groupRequest(Request $request)
    {
        $response = CertificateRequest::groupRequests($request->requests);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
}
