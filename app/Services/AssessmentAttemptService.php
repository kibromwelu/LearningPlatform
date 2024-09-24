<?php
namespace App\Services;
// use Illuminate\Http\Request;

use App\Models\AnswerLog;
use App\Models\AssessmentAttempt;
use App\Models\CourseEnrollment;
use App\Models\LearnerProgress;
use Illuminate\Support\Facades\DB;
// use  App\Models\AssessmentAttempt;
use  App\Models\Question;
use App\Models\Topic;
use Exception;

// use Exception;
class AssessmentAttemptService
{
    public static function attempt($data, $enrollment_id){

        $user = Auth()->user();
        $data['learner_id'] = $user->identity_id;
        $score = 0;
        $count = Topic::getOne($data['topic_id'])->number_of_questions_to_ask;
        $newData['state']='completed';

        LearnerProgress::updateProgess($newData, $data['topic_id'], $data['learner_id']);
        CourseEnrollment::incrementTopics($enrollment_id);
        DB::beginTransaction();
        try {
            $attempt = AssessmentAttempt::create([
                'learner_id' => $data['learner_id'],
                'topic_id' => $data['topic_id'],
                'course_id' => $data['course_id'],
            ]);
            $responseData = [];
            $questionIds = collect($data['answers'])->pluck('question_id');
            $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');
            foreach ($data['answers'] as $answer) {
                $isCorrect = $questions[$answer['question_id']]->answer_key == $answer['learner_answer'];
                $answer['correctAnswer'] = $questions[$answer['question_id']]->answer_key;
                $answer['isCorrect'] = $isCorrect;
                array_push($responseData,$answer);
                // $data['answers']->answer_key = $questions[$answer['question_id']]->answer_key;
                // $data['answers']['iscorrect'] = $isCorrect;

                if ($isCorrect) {
                    $score++;
                }
                
                AnswerLog::register([
                    'assessment_attempt_id' => $attempt->id,
                    'question_id'=>$answer['question_id'],
                    'learner_answer' => $answer['learner_answer'],
                    'is_correct' => $isCorrect
                ]);
            }
            $attempt->update(['score' => $score]);
            DB::commit();
            return [
                'attempt_id' => $attempt->id,
                'score' => $score,
                'answers'=>$responseData,
                'number_of_questions'=>$count,
                'answers'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Can't record assessment attempt".$e, 400);
        }
    }
}    
