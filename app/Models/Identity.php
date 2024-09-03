<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Identity extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'first_name',
        'middle_name',
        'last_name',
        'mother_name',
        'sex',
        'birth_date',
        'birth_place',
        'blood_type',
        'skin_color',
        'eye_color',
        'disability',
        'isoCode',
        'state',
        'status',
    ];
    protected $hidden = ['deleted_at', 'updated_at'];
    public function scopeActive(Builder $query): void
    {
        $query->where('state', '<>', -1);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function credential()
    {
        return $this->hasOne(Credential::class);
    }
    public static function createIdentity($data){
        $identity = self::create($data);
        return $identity;
    }
    public static function updateIdentity($data, $identity_id){
        $identity = self::where('id', $identity_id)->first();
        // dd($identity);
        $identity->update($data);
        return $identity;
    }
    public static function getAll($numOfItems){
      return self::with(['profile','address'])->paginate($numOfItems);
    }
    public static function getOne($id){
        // dd($id);
        $identity = self::where('id',$id)->with(['profile','address'])->first();
        // dd($identity);
        return $identity;
    } 
    public static function calculateAge($birth_date)
    {
        return Carbon::parse($birth_date)->age;
    }
}
