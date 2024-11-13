<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Course extends Model
{
    use HasFactory;
    use  HasUuids;
    protected $fillable = [

        'course_name',
        'category',
        'state'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function topics()
    {
        return $this->hasManyThrough(Topic::class, Module::class);
    }
    public function examrequests()
    {
        return $this->hasMany(ExamRequest::class);
    }
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rated');
    }
    public function learnerprogress()
    {
        return $this->hasMany(LearnerProgress::class);
    }
    public function assessmentattempt()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
    public function discussions()
    {
        return $this->hasMany(CourseDiscussion::class);
    }

    public static function registerCourse($data)
    {
        return self::create($data);
    }

    public static function updateCourse($data, $courseId)
    {

        $course = self::find($courseId);
        return $course->update($data);
    }

    public static function getAll()
    {
        $courses = self::get();
        foreach ($courses as &$course) {
            $course->rating = Rating::getRating($course->id) ?? 0;
        }
        return $courses;
    }
}
