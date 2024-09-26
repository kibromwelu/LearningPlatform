<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'learner_id',
        'state',
        'approved_by',
        'approved_at',
        'authorized_by',
        'authorized_at'
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class, 'learner_id');
    }
    public static function getAll()
    {
        return self::with('learner.identity', 'course')->get();
    }
    public static function registerExamRequest($data)
    {
        $data['learner_id'] = Auth()->user()->identity_id;
        return self::create($data);
    }
    public static function updateExamRequest($data, $id)
    {
        $request = self::findOrFail($id);
        $request->update($data);
        return $request;
    }
    public static function deleteRequest($id)
    {
        $request = self::findOrFail($id);
        return $request->delete();
    }
    public static function approveRequest($id)
    {
        $data['state'] = 'approved';
        $data['approved_at'] = Carbon::now();
        $data['approved_by'] = Auth()->user()->identity_id;
        return self::updateExamRequest($data, $id);
    }
    public static function authorizeExamRequest($id)
    {
        $examRequest = self::find($id);
        if ($examRequest->state == 'approved') {

            $data['state'] = 'authorized';
            $data['authorized_at'] = Carbon::now();
            $data['authorized_by'] = Auth()->user()->identity_id;
            // dd($data);
            return self::updateExamRequest($data, $id);
        }
        throw new \Exception('It should be approved first', 400);
    }
}
