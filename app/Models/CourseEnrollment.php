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
    public function identity()
    {
        return $this->belongsToThrough(Identity::class,Learner::class)->select('id', 'first_name', 'last_name');
    }    

    public static function registerEnrollment($data){
        
       $user=  Auth()->user();
    //    dd($user);
       $data['learner_id'] = $user->identity_id;
        return self::create($data);
    } 
    public static function getAll($course_id, $request){
    $query = CourseEnrollment::query();
    $query->where('course_id', $course_id);
    $query->when($request->has('learner_id'), function ($q) use ($request) {
        $q->where('learner_id', $request->learner_id)
        ->with(['course' => function ($query) {
            $query->withCount('topics'); 
        }]);  
    });

    $query->when($request->has('course_id'), function ($q) use ($request) {
        $q->where('course_id', $request->course_id)
        ->with(['learner.identity']);
    });

    $query->when($request->has('subscription_id'), function ($q) use ($request) {
        $q->where('subscription_id', $request->subscription_id)
        ->with(['course' => function ($query) {
            $query->withCount('topics');
        }, 'learner.identity']);
    });

    $query->when($request->has('state'), function ($q) use ($request) {
        $q->where('state', $request->state)
        ->with(['course' => function ($query) {
            $query->withCount('topics');
        }, 'learner.identity']);
    });
    if (empty($request->query())) {
        $query->with(['course' => function ($query) {
            $query->withCount('topics');
        }, 'learner.identity']);
    }
    $enrollments = $query->get();
    $enrollments->each(function ($enrollment) {
        $course = $enrollment->course;
        if (!$course) {
            // echo "Course not found for enrollment ID: " . $enrollment->id . '<br>';
            return;
        }
        $completedTopics = $enrollment->completed_topics;
        $totalTopics = $course->topics_count ?? 0;
        $progress = $totalTopics > 0 ? ($completedTopics / $totalTopics) * 100 : 0;
        $enrollment->progress = $progress;
    });
    return $enrollments;
    }


    public static function getOne($id){
        return  self::with(['learner','course','subscription'])->find($id);
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

    public static function incrementTopics($id){
        $course_enrollement = self::find($id);
        $course_enrollement->increment('completed_topics');
    }
}
