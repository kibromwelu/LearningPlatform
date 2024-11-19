<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Http\Requests\StorePollRequest;
use App\Http\Requests\UpdatePollRequest;
use App\Models\PollChoice;
use App\Models\PollVotes;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Poll::getAll();
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }
    public function getPollChosers($pollChoiceId)
    {

        $response =  PollChoice::with('votes.user')->find($pollChoiceId);;
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }

    public function store(StorePollRequest $request)
    {

        $response = Poll::store($request->validated());
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($pollId)
    {
        $poll = Poll::getPoll($pollId);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $poll], 200);
    }

    public function update(UpdatePollRequest $request, Poll $poll)
    {
        $response = Poll::updatePoll($request->validated(), $poll);
        return response()->json(['error' => false, 'message' => "updated successfully", 'data' => $response], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poll $poll)
    {
        $response = $poll->delete();
        return response()->json(['error' => false, 'message' => "deleted successfully"], 202);
    }
}
