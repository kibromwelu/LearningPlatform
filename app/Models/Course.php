<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Course extends Model
{
    use HasFactory;
    use  HasUuids;
    protected $fillable=[
       
        'course_name',
        'category'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }
    public function learnerprogress()
    {
        return $this->hasMany(LearnerProgress::class);
    }
    public function assessmentattempt()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
    public static function registerCourse($data){
        return self::create($data);
    }
}
