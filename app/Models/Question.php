<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Choice;
class Question extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    protected $fillable = [
        'topic_id',
        'question',
        'answer_key'
    ];
    protected $hidden = [ 
        "deleted_at",
        "created_at",
        "updated_at"
    ];
    public function choices()
    {
        return $this->hasMany(Choice::class, 'question_id');
    }
    public function correctChoice()
    {
        return $this->belongsTo(Choice::class, 'answer_key');
    }

    public static function updateQuestion($data){
        $question = self::where('id', $data['question_id'])->get()->first();

        if($question) return $question->update($data);

        else  throw new \Exception("Question no longer exists", 400);
    }

    public static function getAll($topic_id){
        return self::where('topic_id', $topic_id)->with(['choices:id,content,question_id'])
        ->select('id', 'question', 'topic_id')->inRandomOrder()->take(3)->get();
    }
    public static function register($data){
        return self::create($data);
    }
    
    public static function getTopicQuestions($topic_id, $number){
        return self::where('topic_id', $topic_id)->with(['choices:id,content,question_id'])
        ->select('id', 'question', 'topic_id')->inRandomOrder()->take($number)->get();
    }
}
