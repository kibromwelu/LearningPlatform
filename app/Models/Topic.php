<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Topic extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'module_id',
        'name',
        'description',
        'minutes',
        'number_of_questions_to_ask'
    ];

    public function assessmentattempt()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
   
    public static function register($data){
        return self::create($data);
    }

    Public static function getAll(){
        return self::get();
    }
    public static function getOne($id){
        return self::find($id);
    }
}
