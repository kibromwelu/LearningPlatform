<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentAttempt extends Model
{
    use HasFactory;
    use HasUuids;
    use softDeletes;


    protected $fillable = [
        'learner_id',
        'course_id',
        'topic_id',
        'score',

    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
    public function answer()
    {
        return $this->hasMany(AnswerLog::class);
    }
    public static function getAll($request){
         // Get all query parameters
         $queryParams = $request->query();
         // Start building the query
         $query = self::query();

         // Add conditions based on query parameters
         if (!empty($queryParams)) {
             $query->where($queryParams);
 
             // Conditional logic for eager loading based on query parameters
             $query->when($request->has('learner_id'), function ($q) {
                 $q->with('course');  // Populate course when searched by learner_id
             });
 
             $query->when($request->has('course_id'), function ($q) {
                 $q->with('learner');  // Populate learner when searched by course_id
             });
 
             $query->when($request->has('state'), function ($q) {
                 $q->with(['course', 'learner']);  // Populate both course and learner when searched by status
             });
         } else {
             // Handle case when no query parameters are provided
             $query->with(['course', 'learner']); // Optionally, load both relationships by default
         }
         $results = $query->get();
 
         // Return the results as a JSON response
         return $results;
    }

    public static function getOne($id){
        return self::with('learner', 'course', 'topic')->find($id);
    }


    public static function register($data){
        return self::create($data);
    }
    public static function deleteAttempt($id){
        $attempt = self::find($id);
        $attempt->delete();
        return response()->json('Assessment attempt deleted');
    }
}
