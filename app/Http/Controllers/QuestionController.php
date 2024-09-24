<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Services\QuestionService;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($topic_id)
    {
        $response = Question::getAll($topic_id);
        return response()->json(['error'=>false, 'data'=>$response],200);
    }

    public function store(StoreQuestionRequest $request)
    {
        $response = Question::register($request->all());
        return response()->json(['error'=>false, 'data'=>$response],201);
    }

  
    public function show( $topic_id)
    {
        $response = QuestionService::getTopicQuestions($topic_id);
        return response()->json(['error'=>false, 'data'=>$response],200);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
}
