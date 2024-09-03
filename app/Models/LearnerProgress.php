<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class LearnerProgress extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;



    protected $fillable = [
        'learner_id',
        'course_id',
        'topic_id',
        'state',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class, 'learner_id');
    }

    public static function registerProgress($data){
        return self::create($data);
    }
    public static function getAll(){
        return self::with('course','learner')->paginate(15);
    }
    public static function getOne($id){
        return self::with('learner','course')->find($id);
    }

    public static function deleteProgress($id){
        $progress = self::find($id);
        $progress->delete();
        return response()->json('Progress deleted');
    }
    public static function updateProgess($data, $id){
        // dd($data);
        if(isset($data['state']) && $data['state']=='completed'){
            $data['completed_at'] = Carbon::now(); 
        }
        $progress = self::find($id);
        // dd($progress);
        $progress->update($data);
        return $progress;
    }

}
