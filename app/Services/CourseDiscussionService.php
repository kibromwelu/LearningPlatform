<?php

namespace App\Services;

use App\Models\CourseDiscussion;
use App\Http\Requests\StoreCourseDiscussionRequest;
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

    public static function updatePost($request, $id)
    {
        // dd($request);
        $data = $request;
        DB::beginTransaction();
        try {
            if ($request['filenames']) {
                $filenames = [];
                $discussion = CourseDiscussion::getOne($id);
                // dd($discussion);
                if ($discussion->filenames) {
                    foreach (json_decode($discussion->filenames) as $filename) {
                        FileService::deleteFile('posts/', $filename);
                    }
                }
                foreach ($request['filenames'] as $file) {
                    $path = 'posts/';
                    $filename = FileService::storeFile($path, $file);
                    array_push($filenames, $filename);
                }
                $file_link = url('/') . '/api/auth/post-file/';
                $data['filenames'] = json_encode($filenames);
                // dd(gettype($data['filenames']));
                $response = CourseDiscussion::updatePost($data, $id);
                $response->filepath = $file_link;
                $response->filenames = json_decode($response->filenames);
                // dd($response);
                DB::commit();
                return $response;
            }
        } catch (Exception $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
