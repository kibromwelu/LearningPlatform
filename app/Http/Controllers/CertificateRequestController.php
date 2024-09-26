<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Http\Requests\StoreCertificateRequestRequest;
use App\Http\Requests\UpdateCertificateRequestRequest;
use Illuminate\Http\Request;

class CertificateRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($course_id, Request $request)
    {
        $response = CertificateRequest::getAll($course_id, $request->state);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificateRequestRequest $request, $courseId)
    {
        //
        $response = CertificateRequest::register($request->validated());
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 201);
    }


    public function show($course_id, $id)
    {
        //
        $response = CertificateRequest::getOne($id);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificateRequestRequest $request, CertificateRequest $certificateRequest, $course_id, $id)
    {
        //
        $response = CertificateRequest::updateRequest($request->validated(), $course_id, $id);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }

    public function destroy($course_id, $id)
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
    public static function authorizeRequest(Request $requestIds)
    {
        $response = CertificateRequest::authorizeRequest($requestIds);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 201);
    }
    public static function groupRequest(Request $request)
    {
        $response = CertificateRequest::groupRequests($request->requests);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 201);
    }
}
