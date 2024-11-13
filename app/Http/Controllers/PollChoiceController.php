<?php

namespace App\Http\Controllers;

use App\Models\PollChoice;
use App\Http\Requests\StorePollChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use App\Http\Requests\UpdatePollChoiceRequest;
use App\Models\PollVotes;

class PollChoiceController extends Controller
{

    public function store(StorePollChoiceRequest $request) {}



    public function update(UpdateChoiceRequest $request, $pollId)
    {
        $response = PollVotes::choosePoll($request->validated(), $pollId);
        return response()->json(['error' => false, 'message' => 'chose successfully', 'data' => $response], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollChoice $pollChoice)
    {
        //
    }
}
