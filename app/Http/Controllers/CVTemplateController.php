<?php

namespace App\Http\Controllers;

use App\Models\CVTemplate;
use App\Http\Requests\StoreCVTemplateRequest;
use App\Http\Requests\UpdateCVTemplateRequest;
use App\Services\FileService;

class CVTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CVTemplate::getAll();
        // $file_link = url('/') . '/api/learning/cv-file/';
        return response()->json(['data' => $response, 'error' => false]);
    }
    public function getTemplateFile($filename)
    {
        // dd($filename);
        return  FileService::getFile('templates/', $filename);
    }
    public function store(StoreCVTemplateRequest $request)
    {
        //
        $response = CVTemplate::store($request);
        return response()->json(['error' => false, 'data' => $response]);
    }


    public function show(CVTemplate $cVTemplate)
    {
        //
    }


    public function update(UpdateCVTemplateRequest $request, CVTemplate $cVTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CVTemplate $cVTemplate)
    {
        //
    }
}
