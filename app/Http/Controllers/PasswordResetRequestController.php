<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use App\Http\Requests\StorePasswordResetRequestRequest;
use App\Http\Requests\UpdatePasswordResetRequestRequest;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;

use function Symfony\Component\String\s;

class PasswordResetRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = PasswordResetRequest::getAll();
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response], 200);
    }


    public function store(Request $request)
    {
        //
        dd($request->all());
        $response = PasswordResetRequest::store($request->validated());
        return response()->json(['error' => false, 'message' => $response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($passwordResetRequestId)
    {
        //
        $response = PasswordResetRequest::getOne($passwordResetRequestId);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response]);
    }

    public function update(UpdatePasswordResetRequestRequest $request)
    {
        $response = PasswordResetRequest::updateResetLinks($request->validated());
        return response()->json(['error' => false, 'message' => $response . ' successfully']);
    }
    public function destroy(PasswordResetRequest $passwordResetRequestId)
    {
        $response = PasswordResetRequest::destroy($passwordResetRequestId);
        return response()->json(['error' => false, 'message' => 'success', 'data' => $response]);
    }
    public function resetApprovedRequests(Request $request)
    {
        // dd("ww");
        $reseponse = PasswordResetRequest::resetPassword($request->all());
        return response()->json(['error' => false, 'message' => 'reset successfully'], 200);
    }
}
