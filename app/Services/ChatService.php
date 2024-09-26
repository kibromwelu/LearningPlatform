<?php

namespace App\Services;

use App\Models\ChatMessages;
use App\Models\UserMessage;
use Exception;
use Illuminate\Support\Facades\DB;

class ChatService
{

    public static function storeChat($data, $friend_id)
    {
        $user_id = Auth()->user()->identity_id;
        if (isset($data['filename'])) {
            $data['filename'] = FileService::storeFile('/posts/', $data['filename']);
        }
        DB::beginTransaction();
        try {
            $message = ChatMessages::store($data);

            $senderCopy =  [
                'identity_id' => $user_id,
                'role' => 'sender',
                'friend_id' => $friend_id,
                'message_id' => $message->id
            ];
            $receiverCopy = [
                'identity_id' => $friend_id,
                'role' => 'reciever',
                'friend_id' => $user_id,
                'message_id' => $message->id
            ];
            $store = UserMessage::store($senderCopy, $receiverCopy);
            DB::commit();
            return UserMessage::getOne($store->id);
           
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception('Something went wrong' . $th, 400);
        }
    }

    public static function getAll($friend_id)
    {
        return UserMessage::getAll($friend_id);
    }

    public static function deleteMessage($userMessageId, $forWhom)
    {
        return UserMessage::deleteMessage($userMessageId, $forWhom);
    }

    public static function updateMessage($data, $userMessageId){
        $message = UserMessage::getOne($userMessageId);
        if($message && $message->role == 'sender'){
          return ChatMessages::updateChat($message->message_id, $data);
        }
        throw new \Exception('You can\'t update this message', 400);
    }
}
