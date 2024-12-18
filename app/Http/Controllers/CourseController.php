<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\FileService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Course::getAll();
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response]);
    }

    public function downloadFile($filename)
    {
        return FileService::downloadFile('/posts/', $filename);
        return response()->json();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $response = Course::registerCourse($request->all());

        return response()->json(['error' => false, 'message' => 'Created successfully', 'data' => $response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
