<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Http\Requests\StoreSignatureRequest;
use App\Http\Requests\UpdateSignatureRequest;
use App\Services\FileService;

class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $response = Signature::getAll();

        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
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
    public function store(StoreSignatureRequest $request)
    {

        $data = $request->validated();
        $data['filename'] = FileService::storeFile('/signatures', $request->filename);

        $response = Signature::store($data);

        return response()->json(['error' => false, 'message' => 'created successfully', 'data' => $response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Signature $signature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Signature $signature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSignatureRequest $request, Signature $signature)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signature $signature)
    {
        //
    }
}
