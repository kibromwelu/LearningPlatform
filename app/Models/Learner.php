<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'identity_id',
        'subscription_id'
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

    public static function registerLearner($data){
        // dd($data);
        $response = self::create($data);
        // dd($response);
        return $response;
    }
    
    public static function getOne($identity_id){
        $learner = self::where('identity_id',$identity_id)->first();
        return $learner;
    }
}
