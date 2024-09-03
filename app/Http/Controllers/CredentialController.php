<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


use App\Models\Credential;
use App\Http\Requests\StoreCredentialRequest;
use App\Http\Requests\UpdateCredentialRequest;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function validatePassword(Request $request)
    {
        $user = Credential::validatePassword($request);
        return response()->json(['error'=> false, 'access_token:'=>$user], 200);
        

        // return response()->json(['message' => 'Invalid password'], 401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCredentialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Credential $credential)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Credential $credential)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCredentialRequest $request, Credential $credential)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credential $credential)
    {
        //
    }
}
