<?php

namespace App\Http\Controllers;


use App\Models\CourseDiscussion;
use App\Http\Requests\StoreCourseDiscussionRequest;
use App\Http\Requests\UpdateCourseDiscussionRequest;
// use App\Services\FileService;
use App\Services\CourseDiscussionservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseDiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($course_id)
    {
        $response = CourseDiscussion::getAll($course_id);
        return response()->json(['error' => false, 'message' => "success", 'data' => $response]);
    }

    public function getPostFile($filename)
    {
        return CourseDiscussion::getPostFile($filename);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseDiscussionRequest $request, $course_id)
    {
        return CourseDiscussionService::storePost($request, $course_id);
    }

    /**
     * Display the specified resource.
     */
    public function show($courseId, $id)
    {
        $response = CourseDiscussion::getOne($id);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response]);
    }
    public function getPostChildren($postId)
    {
        $response = CourseDiscussion::getPostChildren($postId);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseDiscussionRequest $request, $course_id, $discussionId)
    {

        $response = CourseDiscussionservice::updatePost($request->validated(), $discussionId);
        return response()->json(['error' => false, 'message' => "updated", 'data' => $response], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($course_id, $discussionId)
    {
        $res = CourseDiscussion::removePost($discussionId);
        dd($res);
        return response()->json(['error' => false, 'message' => "Item deleted"], 202);
    }
}
