<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeState;
use App\Http\Requests\GroupRequest;
use App\Models\CourseCreatorRequest;
use App\Http\Requests\StoreCourseCreatorRequestRequest;
use App\Http\Requests\UpdateCourseCreatorRequestRequest;
use Illuminate\Http\Request;


class CourseCreatorRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = CourseCreatorRequest::getAll();
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }


    public function store(StoreCourseCreatorRequestRequest $request)
    {
        $response = CourseCreatorRequest::store($request->validated());
        return response()->json(['error' => false, 'message' => 'created successfully', 'data' => $response]);
    }

    /**
     * Display the specified resource.
     */
    public function show($requestId)
    {
        $response = CourseCreatorRequest::getOne($requestId);
        return response()->json(['error' => false, 'message' => 'created successfully', 'data' => $response]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseCreatorRequest $courseCreatorRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseCreatorRequestRequest $request,  $requestId)
    {
        $response = CourseCreatorRequest::updateRequest($request->validated(), $requestId);
        return response()->json(['error' => false, 'message' => 'updated successfully', 'data' => $response], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = CourseCreatorRequest::destroy($id);
        return response()->json(['error' => false, 'message' => 'Item deleted'], 200);
    }
    public function changeState(GroupRequest $request)
    {
        // dd('dd');
        $response = CourseCreatorRequest::changeState($request->validated());
        return response()->json(['error' => false, 'message' => $response]);
    }
}
