<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\LearnerProgress;
use App\Http\Requests\StoreLearnerProgressRequest;
use App\Http\Requests\UpdateLearnerProgressRequest;


class LearnerProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $response = LearnerProgress::getAll();
        return response()->json(['error'=>false, 'message'=>'success', 'data'=>$response],201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLearnerProgressRequest $request)
    {
        //
        $response = LearnerProgress::registerProgress($request->except('state'));
        return response()->json(['error'=>false, 'message'=>'Progress recorded successfuly', 'data'=>$response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $response = LearnerProgress::getOne($id);
        return response()->json(['error'=>true, 'message'=>'success', 'data'=>$response]);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLearnerProgressRequest $request, $id)
    {
        //
        $response = LearnerProgress::updateProgess($request->only('state'), $id);
        return response()->json(['error'=>false, 'message'=>'Updated successfully', 'data'=>$response]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $response = LearnerProgress::deleteProgress($id);
        return response()->json(['error'=>false, "message"=>$response]);
    }
}
