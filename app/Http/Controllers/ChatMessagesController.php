<?php

namespace App\Http\Controllers;

use App\Models\ChatMessages;
use App\Http\Requests\StoreChatMessagesRequest;
use App\Http\Requests\UpdateChatMessagesRequest;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($friend_id)
    {
        $response = ChatService::getAll($friend_id);
        return response()->json(['error'=>false, 'message'=>'success', 'data'=>$response],200);
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
    public function store(StoreChatMessagesRequest $request)
    {
        //
        $response = ChatService::storeChat($request->all());
        return response()->json(['error'=> false, 'message'=>'sent', 'data'=>$response ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($friend_id)
    {
        //
        $response = ChatService::getAll($friend_id);
        return response()->json(['error'=>false, 'message'=>'success', 'data'=>$response],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatMessages $chatMessages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatMessagesRequest $request,  $message_id)
    {
        //
        $response = ChatService::updateMessage($request->validated(), $message_id);
        return response()->json(['error'=>false, 'message'=>'Message updated', 'data'=>$response],202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($message_id,Request $request)
    {
        //
        ChatService::deleteMessage($message_id, $request->forWhom);
        return response()->json(['error'=>false, 'message'=>'Message Deleted'],200);

    }
}
