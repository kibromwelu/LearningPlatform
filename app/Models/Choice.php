<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Choice extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;
    protected $fillable = [
        'question_id',
        'content',
        'isCorrect'
    ];
    public function question(){
     return $this->belongsTo(Question::class);  
    }
    public function getAll(){
        return self::get();
    }
     public static function register($data){
         $reponse = self::create($data);
        if($data['isCorrect']==true){
            $newData['answer_key'] = $reponse->id;
            $newData['question_id'] = $data['question_id'];
            Question::updateQuestion($newData);
        }
        return $reponse;
     }
     public static function getQuestionChoices($question_id){
        return self::where('question_id', $question_id)->get();
     }

}
