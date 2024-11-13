<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Signature extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'identity_id',
        'filename',
        'state',
        'deactivated_at'
    ];

    public function certificates()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    public static function store($data)
    {
        $userId = Auth()->user()->identity_id;
        $data['identity_id'] = $userId;
        $prevSign = self::where('identity_id', $userId)->where('state', 'active')->first();
        DB::beginTransaction();
        try {
            if ($prevSign)
                $prevSign->update(['state' => 'inactive', 'deactivated_at' => Carbon::now()]);
            $curSign = self::create($data);
            DB::commit();
            return $curSign;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception('something went wrong' . $th, 400);
        }
    }
    public static function getAll()
    {
        return self::get();
    }
}
