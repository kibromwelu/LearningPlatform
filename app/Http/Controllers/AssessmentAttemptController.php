<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Services\AssessmentAttemptService;
use App\Models\AssessmentAttempt;
use App\Http\Requests\StoreAssessmentAttemptRequest;
use App\Http\Requests\UpdateAssessmentAttemptRequest;

class AssessmentAttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $response = AssessmentAttempt::getAll($request);
        return response()->json(['error'=> false, 'message'=>'success', 'data'=>$response],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssessmentAttemptRequest $request)
    {
        //
        // dd($request->all());

        $res = AssessmentAttemptService::registerAttempt($request);
        
        $response = AssessmentAttempt::register($request->all());
        return response()->json(['error'=>false, 'message'=>"Attempt recorded", 'data'=>$response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $response = AssessmentAttempt::getOne($id);
        return response()->json(['error'=>true, 'message'=>'success', "data"=>$response],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssessmentAttemptRequest $request, AssessmentAttempt $assessmentAttempt)
    {
        //
        // $response = AssessmentAttempt::
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $response = AssessmentAttempt::deleteAttempt($id);
        return response()->json(['error'=>false, 'message'=>$response], 202);
    }
}
