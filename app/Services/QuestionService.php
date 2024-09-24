<?php

namespace App\Services;

use App\Models\Topic;
use App\Models\Question;

class QuestionService {


    public static function getTopicQuestions($topic_id){
        $topic = Topic::getOne($topic_id);
        
        $questions = Question::getTopicQuestions($topic_id, $topic->number_of_questions_to_ask);
        return $questions;
    }
}