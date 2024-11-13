<?php

namespace App\Services;
// use Illuminate\Http\Request;

use App\Models\AnswerLog;
use App\Models\AssessmentAttempt;
use App\Models\CourseEnrollment;
use App\Models\LearnerProgress;
use Illuminate\Support\Facades\DB;
use  App\Models\Question;
use App\Models\Topic;
use Exception;

// use Exception;
class AssessmentAttemptService
{
    public static function attempt($data, $enrollment_id)
    {
        // dd($data);
        $user = Auth()->user();
        $data['learner_id'] = $user->identity_id;
        $score = 0;
        $count = Topic::getOne($data['topic_id'])->number_of_questions_to_ask;
        $newData['state'] = 'completed';
        dd($newData);
        LearnerProgress::updateProgess($newData, $data['topic_id'], $data['enrollment_id']);

        CourseEnrollment::incrementTopics($enrollment_id);
        DB::beginTransaction();
        try {
            $attempt = AssessmentAttempt::create([
                'enrollment_id' => $data['enrollment_id'],
                'topic_id' => $data['topic_id'],
                'type' => 'quiz'
            ]);

            $responseData = [];
            $questionIds = collect($data['answers'])->pluck('question_id');
            $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');
            foreach ($data['answers'] as $answer) {
                $isCorrect = $questions[$answer['question_id']]->answer_key == $answer['learner_answer'];
                $answer['correctAnswer'] = $questions[$answer['question_id']]->answer_key;
                $answer['isCorrect'] = $isCorrect;
                array_push($responseData, $answer);
                if ($isCorrect) {
                    $score++;
                }

                AnswerLog::register([
                    'assessment_attempt_id' => $attempt->id,
                    'question_id' => $answer['question_id'],
                    'learner_answer' => $answer['learner_answer'],
                    'is_correct' => $isCorrect
                ]);
            }
            $attempt->update(['score' => $score]);
            DB::commit();
            return [
                'attempt_id' => $attempt->id,
                'score' => $score,
                'answers' => $responseData,
                'number_of_questions' => $count,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Can't record assessment attempt" . $e, 400);
        }
    }
    public static function storeFinalExam($data)
    {
        // dd($data);
        $score = 0;
        $count = 0;
        $responseData = [];
        DB::beginTransaction();
        try {
            $attempt = AssessmentAttempt::create([
                'learner_id' => Auth()->user()->identity_id,
                'enrollment_id' => $data['enrollment_id'],
                'type' => 'final'
            ]);
            $questionIds = collect($data['answers'])->pluck('question_id');
            $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');
            foreach ($data['answers'] as $answer) {
                $isCorrect = $questions[$answer['question_id']]->answer_key == $answer['learner_answer'];
                $answer['correctAnswer'] = $questions[$answer['question_id']]->answer_key;
                $answer['isCorrect'] = $isCorrect;
                array_push($responseData, $answer);

                if ($isCorrect) {
                    $score++;
                }
                $count++;
                AnswerLog::register([
                    'assessment_attempt_id' => $attempt->id,
                    'question_id' => $answer['question_id'],
                    'learner_answer' => $answer['learner_answer'],
                    'is_correct' => $isCorrect
                ]);
            }
            $attempt->update(['score' => $score]);
            DB::commit();
            return  'final exam subtmitted successfuly';
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Can't record assessment attempt" . $e, 400);
        }
    }
    public static function getFinalExamQuestions($courseId)
    {
        $response = Question::whereHas('topic.module', function ($query) use ($courseId) {
            $query->where('modules.course_id', $courseId);  // Filter by course_id in the modules table
        })
            ->with(['choices:id,content,question_id']) // Load choices for the question
            ->select('id', 'question', 'topic_id')
            ->inRandomOrder()
            ->take(50)
            ->get();
        return $response;
    }
}
