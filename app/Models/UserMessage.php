<?php

namespace App\Models;

use App\Services\TimeService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMessage extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'identity_id',
        'message_id',
        'role',
        'friend_id'
    ];
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->id = (string) Str::uuid(); // Generate UUID
    //     });
    // }
    public function message()
    {
        return  $this->belongsTo(ChatMessages::class, 'message_id')->select('id', 'content');
    }
    public function receiver()
    {
        return $this->belongsTo(Identity::class, 'friend_id')->select('id', 'first_name', 'last_name');
    }
    public function sender()
    {
        return $this->belongsTo(Identity::class, 'identity_id')->select('id', 'first_name', 'last_name');
    }
    public static function store($sender, $receiver)
    {

        self::create($receiver);
        return  self::create($sender);
    }
    public static function getAll($friendId, $skip = null, $count = null)
    {
        $messages = UserMessage::where('identity_id', Auth()->user()->identity_id)
            ->where('friend_id', $friendId)
            ->orderBy('created_at', 'desc')
            ->skip($skip ?? 0)
            ->take($count ?? 10)
            ->with('message')
            ->get();
        ChatMessages::updateSeenMessagesState(
            self::filterReceivedMessages($messages)
        );
        return TimeService::timeAgo($messages);
    }
    public static function filterReceivedMessages($messages)
    {
        $receivedMessageIds = [];
        foreach ($messages as $message) {
            if ($message->role === 'receiver' && $message->message->state == 'new')
                array_push($receivedMessageIds, $message->message_id);
        }
        return $receivedMessageIds;
    }

    public static function getOne($messageId)
    {
        $iid = Auth()->user()->identity_id;
        return UserMessage::with('message')
            ->where('identity_id', $iid)
            ->findorFail($messageId);
    }
    public static function deleteMessage($userMessageId, $forWhom)
    {
        $userMessage = UserMessage::getOne($userMessageId);
        if ($userMessage->role == 'sender' && $forWhom == 'all') {
            // $r = UserMessage::where('message_id',$userMessage->message_id)->delete();
            // dd($r);
            $chatMessage = ChatMessages::with('usermessages')
                ->findOrFail($userMessage->message_id);
            $chatMessage->usermessages()->delete();
            return $chatMessage->delete();
        }
        return $userMessage->delete();
    }
}
