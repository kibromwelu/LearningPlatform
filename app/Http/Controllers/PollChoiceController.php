<?php

namespace App\Http\Controllers;

use App\Models\PollChoice;
use App\Http\Requests\StorePollChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use App\Http\Requests\UpdatePollChoiceRequest;
use App\Models\Choice;
use App\Models\PollVotes;
use Illuminate\Http\Request;

class PollChoiceController extends Controller
{

    public function store(StorePollChoiceRequest $request) {}



    public function update(UpdateChoiceRequest $request, $pollId)
    {
        $response = PollVotes::choosePoll($request->validated(), $pollId);
        return response()->json(['error' => false, 'message' => 'chose successfully', 'data' => $response], 202);
    }
    public function updateChoice(Request $request,  $choiceId)
    {
        // dd($choice);
        $choice = PollChoice::find($choiceId);
        $data['content'] = $request->content;
        $choice->update($data);
        // dd($choice['content']);
        return response()->json(['error' => false, 'message' => 'updated successfully', 'data' => $choice], 202);
    }
    public function addChoice(Request $request,  $pollId)
    {
        $data['poll_id'] = $pollId;
        $data['content'] = $request->content;
        $response = Choice::create($data);
        return response()->json(['error' => false, 'message' => 'updated successfully', 'data' => $response], 201);
    }

    public function destroy(PollChoice $pollChoice)
    {
        $pollChoice->delete();
        return response()->json(['error' => false, 'message' => 'deleted successfully'], 200);
    }
}
