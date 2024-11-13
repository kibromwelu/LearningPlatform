<?php

namespace App\Http\Controllers;

use App\Models\RefundRequest;
use App\Http\Requests\StoreRefundRequestRequest;
use App\Http\Requests\UpdateRefundRequestRequest;

class RefundRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = RefundRequest::getAll();

        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }


    public function store(StoreRefundRequestRequest $request)
    {
        //
        $response = RefundRequest::store($request->validated());

        return response()->json(['error' => false, 'message' => 'created successfully', 'data' => $response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RefundRequest $refundRequest) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRefundRequestRequest $request)
    {
        //
        $response = RefundRequest::updateRefund($request->validated());

        return response()->json(['error' => false, 'message' => $response . ' successfully'], 202);
    }
    public function destroy($refundRequestId)
    {
        RefundRequest::destroy($refundRequestId);
        return response()->json(['error' => false, 'message' => 'Item deleted successfully',], 202);
    }
}
