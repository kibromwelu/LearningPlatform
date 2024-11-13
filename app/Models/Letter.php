<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'date',
        'To',
        'subject',
        'message',
        'carbon_copy_to',
        'created_by'
    ];
    public static function store($data)
    {
        $data['created_by'] = Auth()->user()->identity_id;
        return self::create($data);
    }
}
