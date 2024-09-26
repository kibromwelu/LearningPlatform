<?php

namespace App\Models;

use App\Services\FileService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessages extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    protected $fillable = ['content', 'filename', 'state'];
    public function userMessages()
    {
        return $this->hasMany(UserMessage::class, 'message_id');
    }
    public function message()
    {
        return  $this->belongsTo(ChatMessages::class, 'message_id')->select('id', 'content');
    }

    public static function store($data)
    {
        return self::create($data);
    }
    public static function getAll()
    {
        return self::with('message', 'sender', 'receiver')->get();
    }
    public static function updateChat($messageId, $data)
    {
        $message = self::findOrFail($messageId);
        if (isset($data['filename']) && $message->filename) {
            $newfile = FileService::storeFile('/posts/', $data['filename']);
            FileService::deleteFile('/posts/', $message->filename);
            $message->filename = $newfile;
        }
        $message->update($data);
        return $message;
    }

    public static function updateSeenMessagesState($messageIds)
    {
        $chats = ChatMessages::whereIn('id', $messageIds)->get();
        foreach ($chats as $chat) {
            $chat->update(['state' => 'seen']);
        }
        return true;
    }
}
