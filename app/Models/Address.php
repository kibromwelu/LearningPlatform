<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\VarDumper\Cloner\Data;

class Address extends Model
{
    use HasFactory;
    use HasUuids;
    protected $fillable = [
        'identity_id',
        'residence_id',
        'phone',
        'email',
        'website',
        'pobox',
        'house_number',
        'address_line_1',
        'address_line_2',
        'specific_location',
        'tabia',
        'city',
        'country',
        'deleted_at',
        'state',
        'status',
    ];
    protected $hidden = ['created_at', 'updated_at','deleted_at'];
    public function identity()
    {
        return $this->belongsTo(Models::IDENTITY)
            ->select(
                'id',
                'id_number',
                'first_name',
                'middle_name',
                'last_name'
            );
    }

    public static function updateAddressInfo($request, $identityId)
    {
        $model = Address::where('identity_id', $identityId)->first();
        $model->update($request);
        $model->save();
        $account = User::where('identity_id', $identityId)->first();
        $model->is_email_verified = $account ? ($account->email_verified_at ? true : false) : false;
        return $model;
    }

    public static function updateAddress($data,$identityId)
    {
        // dd($data);
        $address = self::where('identity_id', $identityId)->first();
        // dd($product);
        $address->update($data);
        return $address;
    }

    public static function getMyAddress($identityId){
        return self::where('identity_id', $identityId)->first();
    }
    public static function  register($data){
        return self::updateOrCreate(['identity_id'=>$data['identity_id']],$data);
    }
}
