<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Topic extends Model
{
    use HasFactory;
    use HasUuids;

    public function assessmentattempt()
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
}
