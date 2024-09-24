<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    protected $fillable = [
        'name','course_id'
    ];

    public function topic(){
        $this->hasMany(Topic::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public static function getAll(){
        return self::get();
    }
    public static function store($data){
        return self::create($data);
    }

}
