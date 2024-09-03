<?php
namespace App\Services;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use  App\Models\AssessmentAttempt;
// use  App\Models\AnswerLog;
// use Exception;
class AssessmentAttemptService
{
    public static function registerAttempt($request)
    {
        $submittedAnswers = $request->answers; // Array of ['question_id' => ..., 'learner_answer' => ...]
        // Initialize score and count
        $score = 0;
        $count = 0;
    
        // Extract question IDs from submitted answers

        // $questionIds = array_column($submittedAnswers, 'question_id');
        // $correctAnswers = Question::whereIn('id', $questionIds)
        //     ->get()
        //     ->keyBy('id')  // Key by 'id' to get an associative array
        //     ->map(function($question) {
        //         return $question->answer_choice_id;
        // })->toArray();
        
        $correctAnswers = [
            1 => ['answer_choice_id' => 'C', 'id' => "1"],
            2 => ['answer_choice_id' => 'A', 'id' => "2"],
            3 => ['answer_choice_id' => 'B', 'id' => "3"],
        ];
        // Start transaction
        // DB::beginTransaction();
    
        try {
            $attemptData = [
                'learner_id'=>$request->learner_id,
                'course_id'=>$request->course_id,
                'topic_id'=>$request->topic_id
            ];
    
            // $answerLogs = []; // To store bulk insert data
    
            // Step 3: Check each submitted answer for correctness and prepare answer logs
            foreach ($submittedAnswers as $submittedAnswer) {
                $questionId = $submittedAnswer['question_id'];
                $learnerAnswer = $submittedAnswer['learner_answer'];
    
                // Ensure the correct answer exists for the given question ID
                if (isset($correctAnswers[$questionId])) {
                    // Check if the learner's answer is correct
                    $isCorrect = $correctAnswers[$questionId]['answer_choice_id'] == $learnerAnswer;
    
                    // Increment score if correct
                    if ($isCorrect) {
                        $score++;
                    }
    
                    // Prepare data for bulk insert
                    // $answerLogs[] = [
                    //     // 'assessment_attempt_id' => $assessmentAttempt->id, // Uncomment when using database
                    //     'question_id' => $questionId,
                    //     'learner_answer' => $learnerAnswer,
                    //     'isCorrect' => $isCorrect,
                    //     'created_at' => now(),
                    //     'updated_at' => now(),
                    // ];
    
                    $count++;
                } 
            }
            $attemptData['score'] = $score;
            dd($attemptData);
            // $attempt = AssessmentAttempt::registerAttempt($attemptData);
            // foreach ($answerLogs as &$answerLog) {  // Using reference to modify the array in place
            //     $answerLog['assessment_attempt_id'] = $assessmentAttempt->id; // Add the attempt_id
            // }
            // AnswerLog::insert($answerLogs);
            // Commit the transaction
            // DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Assessment submitted successfully', 'score' => $score]);
    
        } catch (\Exception $e) {
            // Rollback the transaction on error
            // DB::rollback(); // Uncomment when using database
            return response()->json(['success' => false, 'message' => 'Error submitting assessment', 'error' => $e->getMessage()]);
        }
    }
    
}