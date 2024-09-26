<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;

class ChoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Choice::getAll();
        return response()->json(['error'=> false, 'data'=>$response], 200);
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
    public function store(StoreChoiceRequest $request)
    {
        //
        $response = Choice::register($request->validated());
        return response()->json(['error'=> false, 'data'=>$response], 200);
    }
    public function show(Choice $choice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Choice $choice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChoiceRequest $request, Choice $choice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Choice $choice)
    {
        //
    }
}
