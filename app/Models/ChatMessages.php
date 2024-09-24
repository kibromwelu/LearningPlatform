<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessages extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    protected $fillable = ['content'];

    public function userchats(){
        $this->hasMany(ChatMessages::class);
    }
    public static function store($data){
        return self::create($data);
    }

    public static function getAll(){
        return self::with('message','sender', 'receiver')->get();
    }

}
