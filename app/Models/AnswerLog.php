<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AnswerLog extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;

    protected $fillable=[
        'assessment_attempt_id',
        'question_id',
        'learner_answer',
        'is_correct'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function assessment_attempt()
    {
        return $this->belongsTo(AssessmentAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public static function getAll(){
        return self::all();
    }
    public static function getOne($id){
        return self::find($id);
    }

    public static function register($data){
        return self::create($data);
    }
}
