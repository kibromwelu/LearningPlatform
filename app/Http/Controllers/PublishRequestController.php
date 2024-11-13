<?php

namespace App\Http\Controllers;

use App\Models\PublishRequest;
use App\Http\Requests\StorePublishRequestRequest;
use App\Http\Requests\UpdatePublishRequestRequest;

class PublishRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = PublishRequest::getAll();
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response]);
    }

    public function getRejectedPublishRemark($courseId)
    {
        $response = PublishRequest::getRejectedPublishRemark($courseId);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 201);
    }
    public function store(StorePublishRequestRequest $request)
    {
        $response = PublishRequest::store($request->validated());
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PublishRequest $publishRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PublishRequest $publishRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublishRequestRequest $request)
    {
        $response = PublishRequest::updateRequest($request);
        return response()->json(['error' => false, 'message' => $response . '  succsesfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PublishRequest $publishRequest)
    {
        //
    }
}
