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
        'enrollment_id',
        'topic_id',
        'score',
        'type'

    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class);
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

    public static function getAll($request)
    {

        $queryParams = $request->query();
        $query = self::query();
        if (!empty($queryParams)) {
            $query->where($queryParams);
            $query->when($request->has('state'), function ($q) {
                $q->with(['course', 'learner']);
            });
        } else {
            $query->with(['enrollment.course', 'enrollment.learner']);
        }
        $results = $query->get();
        return $results;
    }

    public static function getOne($id)
    {
        return self::with('learner', 'course', 'topic')->find($id);
    }


    public static function register($data)
    {
        return self::create($data);
    }
    public static function deleteAttempt($id)
    {
        $attempt = self::find($id);
        $attempt->delete();
        return response()->json('Assessment attempt deleted');
    }
}
