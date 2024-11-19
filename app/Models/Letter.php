<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Letter extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'date',
        'to',
        'language',
        'refNumber',
        'subject',
        'message',
        'carbon_copy_to',
        'created_by'
    ];
    protected $casts = [
        'carbon_copy_to' => 'array',
    ];
    public  function writer(){
        return $this->belongsTo(Identity::class, 'created_by');
    }
    public static function store($data)
    {
        // Log::info($data);
        $data['carbon_copy_to'] = $data['carbon_copy_to'];
        $data['created_by'] = Auth()->user()->identity_id;
        return self::create($data);
    }
}
