<?php

namespace App\Http\Controllers;

use App\Models\AnswerLog;
use App\Http\Requests\StoreAnswerLogRequest;
use App\Http\Requests\UpdateAnswerLogRequest;

class AnswerLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $response = AnswerLog::getAll();
         return response()->json(['error'=> false, 'data'=>$response], 200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnswerLogRequest $request)
    {
        //
        $response = AnswerLog::register($request->all());
        return response()->json(['error'=>false, 'message'=>'Created successfully', 'data'=>$response],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $response = AnswerLog::getOne($id);
         return response()->json(['error'=>false, 'data'=>$response], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswerLogRequest $request, AnswerLog $answerLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnswerLog $answerLog)
    {
        //
    }
}
