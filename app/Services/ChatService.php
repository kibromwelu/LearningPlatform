<?php
namespace App\Services;

use App\Models\ChatMessages;
use App\Models\UserMessage;
use Exception;

class ChatService{
    public static function storeChat($data){
        $user_id = Auth()->user()->identity_id;
        $messageData['content'] = $data['content'];

        $senderCopy['identity_id'] = $user_id;
        $senderCopy['role'] = 'sender';
        $senderCopy['friend_id'] = $data['friend_id'];

        $receiverCopy['identity_id'] = $data['friend_id'];
        $receiverCopy['role'] = 'reciever';
        $receiverCopy['friend_id'] = $user_id;

        if(isset($data['filename'])){
            $messageData['filename'] = FileService::storeFile('/posts/', $data['filename']); 
        }
       $message = ChatMessages::store($messageData);
       $senderCopy['message_id'] = $message->id;
       $receiverCopy['message_id'] = $message->id;
        UserMessage::store($senderCopy, $receiverCopy);
       return $data= [
        'id'=>$message->id,

        'sender_id'=>$user_id,
        'receiver_id'=>$data['friend_id']
       ];
    }
    public static function getAll($friend_id){
        return UserMessage::getAll($friend_id);
    }
    public static function deleteMessage($message_id, $forWhom){
        return UserMessage::deleteMessage($message_id, $forWhom);
    }
    public static function updateMessage($data, $message_id){
        return UserMessage::updateMessage($data, $message_id);
    }
}