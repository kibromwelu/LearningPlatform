<?php

namespace App\Models;

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
    public function message(){
       return  $this->belongsTo(ChatMessages::class, 'message_id')->select('id', 'content');
    }
    public function receiver(){
        return $this->belongsTo(Identity::class, 'friend_id')->select('id', 'first_name', 'last_name');
    }
    public function sender(){
       return $this->belongsTo(Identity::class, 'identity_id')->select('id', 'first_name', 'last_name');
    }
     public static function store($sender,$receiver){
        
        $data[0]=$sender;
        $data[1]=$receiver;
         self::create($sender);
         self::create($receiver);
         return true;
     }
     public static function getAll($friend_id){
        return UserMessage::where('identity_id',Auth()->user()->identity_id)->where('friend_id',$friend_id)->with(['message','sender','receiver'])->get();
        
     }
        public static function deleteMessage($message_id, $forWhom){
            if($forWhom == 'all'){
                
                return UserMessage::where('message_id',$message_id)->delete();
            }else if($forWhom == 'me') {
                $user_id = Auth()->user()->identity_id;
    
                return UserMessage::where('message_id',$message_id)->where('identity_id',$user_id)->delete();
                // dd($data);
    
            }else{
                return false;
            }
        }

        public static function updateMessage($data, $message_id){
            $message = UserMessage::where('message_id',$message_id)->where('identity_id',Auth()->user()->identity_id)->get()->first();
            // dd($message->role);  
            if($message && $message->role == 'sender'){
               return  $message->delete();
            }

            throw new \Exception('You can\'t update this message', 400);
            // throw
            
        }
     
}
