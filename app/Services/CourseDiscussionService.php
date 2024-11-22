<?php

namespace App\Services;

use App\Models\CourseDiscussion;
use App\Http\Requests\StoreCourseDiscussionRequest;
use App\Http\Requests\UpdateCourseDiscussionRequest;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class CourseDiscussionService
{
    public static function storePost(Request $request, $course_id)
    {
        $filename = '';
        $file_link = '';
        $filenames = [];
        // Log::info($request->learner_id);
        if ($request->hasFile('filenames')) {
            foreach ($request->filenames as $file) {
                $path = 'posts/';
                $filename = FileService::storeFile($path, $file);
                array_push($filenames, $filename);
            }
            $file_link = url('/') . '/api/auth/post-file/';
        }
        Log::info($filenames);
        $data = $request->validated();
        $data['filenames'] = json_encode($filenames);
        $data['course_id'] = $course_id;
        $data['learner_id'] = Auth()->user()->identity_id;

        $response = CourseDiscussion::register($data);
        $response->filepath = $file_link;
        return response()->json(['error' => false, 'message' => "created successfuly", 'discussion' => $response,], 201);
    }

    public static function updatePost(UpdateCourseDiscussionRequest $request, $id)
{
    $data = $request->validated();
    $file_link = '';  // Base URL for file links
    DB::beginTransaction();
    try {
        // Initialize filenames array
        $discussion = CourseDiscussion::getOne($id);
        
        $filenames = $request->has('filenames.existing') ? $request->input('filenames.existing'): [];
        // Handle new photos (files)
        if ($request->hasFile('filenames.new')) {
            $newPhotos = $request->file('filenames.new');
            foreach ($newPhotos as $file) {
                $path = 'posts/';
                $filename = FileService::storeFile($path, $file);
                $filenames[] = $filename;  // Store the new filename
            }
        }

        // Set the file URL for frontend (for new uploaded files)
        $file_link = url('/') . '/api/auth/post-file/';
        $data['filenames'] = json_encode($filenames);  // Combine existing and new filenames

        // Update the post data in the database
        $response = CourseDiscussion::updatePost($data, $id);
        $response->filepath = $file_link;
        $response->filenames = json_decode($response->filenames);  // Return filenames in the response

        DB::commit();
        return $response;

    } catch (Exception $th) {
        DB::rollBack();
        throw $th;
    }
}

}
