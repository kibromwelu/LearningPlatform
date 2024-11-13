<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Rating::getAll();
        return response()->json(['error' => true, 'message' => 'created', 'data' => $response], 201);
    }

    public function store(StoreRatingRequest $request)
    {
        $response = Rating::store($request->validated());
        return response()->json(['error' => true, 'message' => 'created', 'data' => $response], 201);
    }


    public function show(Rating $rating) {}


    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
