<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CourseEnrollment extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable =[
        'learner_id',
        'course_id',
        'subscription_id',
        'state'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function course()
    {
        return $this->belongsTo(Course::class);//->select(['']);
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);//->select(['']);
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }    

    public static function registerEnrollment($data){

        return self::create($data);
    } 
    public static function getAll($request){
            $queryParams = $request->query();
            $query = self::query();

            // Add conditions based on query parameters
            if (!empty($queryParams)) {
                $query->where($queryParams);
    
                // Conditional logic for eager loading based on query parameters
                $query->when($request->has('learner_id'), function ($q) {
                    $q->with(['course', 'subscription']);  // Populate course when searched by learner_id
                });
    
                $query->when($request->has('course_id'), function ($q) {
                    $q->with(['learner','subscription']);  // Populate learner when searched by course_id
                });
                $query->when($request->has('subscription_id'), function ($q) {
                    $q->with(['course','learner']);  // Populate learner when searched by subscription_id
                });
    
                $query->when($request->has('state'), function ($q) {
                    $q->with(['course', 'learner','subscription']);  // Populate both course and learner when searched by status
                });
            } else {
                // Handle case when no query parameters are provided
                $query->with(['course', 'learner','subscription']); // Optionally, load both relationships by default
            }
            // dd($query);
            $results = $query->get();
    
            // Return the results as a JSON response
            return $results;
    }

    public static function getOne($id){
        return  self::with(['learner','course'])->find($id);
    }
    public static function deleteEnrollment($id){
        $enrollment = self::find($id);
        $enrollment->delete();

        return response()->json('Item Deleted');

    }
    public static function updateEnrollment($data, $id){
        $enrollment = self::find($id);
        $enrollment->update($data);
        return $enrollment;
    }

    public static function getMyEnrollments($user){
        $learner = Learner::getOne($user->identity_id);
        $enrollments = self::where('learner_id', $learner->id)->with('course')->get();
        return $enrollments;
    }
}
