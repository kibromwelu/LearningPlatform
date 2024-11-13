<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'rater_id',
        'rated_type',
        'rated_id',
        'rating',
        'is_helpful'
    ];
    public function rated()
    {
        return $this->morphTo();
    }

    public function rater()
    {
        return $this->belongsTo(Identity::class)->select('id', 'first_name', 'last_name');
    }

    public static function store($data)
    {
        $data['rater_id'] = Auth()->user()->identity_id;
        $data['rated_id'] = $data['rated'];

        if ($data['type'] == 'user') {
            $data['rated_type'] = Identity::class;
        } else {
            $data['rated_type'] = Course::class;
        }
        unset($data['rated']);
        return self::create($data);
    }

    public static function getRating($ratedId)
    {
        return self::where('rated_id', $ratedId)->avg('rating');
    }

    public static function getAll()
    {

        return self::with('rated', 'rater')->get();
    }
}
