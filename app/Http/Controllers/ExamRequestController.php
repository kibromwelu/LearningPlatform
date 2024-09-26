<?php

namespace App\Http\Controllers;

use App\Models\ExamRequest;
use App\Http\Requests\StoreExamRequestRequest;
use App\Http\Requests\UpdateExamRequestRequest;

class ExamRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response  = ExamRequest::getAll();
        return response()->json(['error' => false, 'data' => $response], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamRequestRequest $request)
    {
        //
        $response = ExamRequest::registerExamRequest($request->validated());
        return response()->json(['error' => false, 'data' => $response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamRequest $examRequest) {}



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamRequestRequest $request,  $id)
    {
        $response = ExamRequest::updateExamRequest($request->validated(), $id);
        return response()->json(['error' => false, 'data' => $response], 202);
    }

    public function approveExamRequest($id)
    {
        $response = ExamRequest::approveRequest($id);
        return response()->json(['error' => false, 'data' => $response], 202);
    }
    public function authorizeExamRequest($id)
    {
        $response = ExamRequest::authorizeExamRequest($id);
        return response()->json(['error' => false, 'data' => $response], 202);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ExamRequest::deleteRequest($id);
        return response()->json(['error' => false, 'message' => 'Item Deleted'], 200);
    }
}
