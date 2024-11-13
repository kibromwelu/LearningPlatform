<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\Exists;

class PollChoice extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'selected_by',
        'content',
        'poll_id'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
    public function votes()
    {
        return $this->hasMany(PollVotes::class);
    }
    public static function store($data)
    {

        return self::insert($data);
    }
}
