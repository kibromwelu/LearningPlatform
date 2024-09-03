<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Services\CourseEnrollmentService;
use App\Models\CourseEnrollment;
use App\Http\Requests\StoreCourseEnrollmentRequest;
use App\Http\Requests\UpdateCourseEnrollmentRequest;


class CourseEnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $response = CourseEnrollment::getAll($request);
        return response()->json(['error'=>false, 'message'=>'success', 'data'=>$response],200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseEnrollmentRequest $request)
    {
        //

        $response = CourseEnrollmentService::registerEnrollment($request->validated());
        return response()->json(['error'=>false, 'message'=>"Enrolled successfully", 'data'=>$response],201);
    }
    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        //
        $enrollment = CourseEnrollment::getOne($id);
        return response()->json(['error'=>false, 'message'=>'success', 'data'=>$enrollment],200);
    }

    public function getMyEnrollments(){
        $enrollments = CourseEnrollment::getMyEnrollments(auth()->user());
        return response()->json(['error'=>false, 'message'=>'success','enrollments'=>$enrollments]);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseEnrollmentRequest $request,  $id)
    {
        //
        $response = CourseEnrollment::updateEnrollment($request->validated(),$id);
        return response()->json(['error'=>true, 'message'=>'Updated successfully', 'data'=>$response],202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseEnrollmentId)
    {
        $response = CourseEnrollment::deleteEnrollment($courseEnrollmentId);
        return response()->json(['error'=>false, 'message'=>$response],202);
    }
}
