<?php
namespace App\Services;

use App\Models\CourseDiscussion;
use App\Http\Requests\StoreCourseDiscussionRequest;
use App\Services\FileService;
class CourseDiscussionService
{
    public static function storePost(StoreCourseDiscussionRequest $request,$course_id){
        $filename = '';
        $file_link = '';
        if($request->hasFile('filename')){
            $path = 'uploads/posts/';
            $filename = FileService::storeFile($path,$request->filename);
            $file_link = url('/').'/api/auth/post-file/'.$filename;
        }
        $data = $request->validated();
        $data['filename'] = $filename;
        $data['course_id']=$course_id;
        $data['learner_id']=Auth()->user()->identity_id;

        $response = CourseDiscussion::register($data);
        $response->filename = $file_link;
        return response()->json(['error'=>false, 'message'=>"created successfuly", 'discussion'=>$response,], 201);
    }
}