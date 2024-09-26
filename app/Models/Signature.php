<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'identity_id',
        'filename'
    ];

    public function certificates()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    public static function store($data)
    {
        $data['identity_id'] = Auth()->user()->identity_id;
        return self::create($data);
    }
    public static function getAll()
    {
        return self::get();
    }
}
