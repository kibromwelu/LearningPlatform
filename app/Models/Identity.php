<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Identity extends Model
{
    use HasFactory;
    use HasUuids;
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
        'state',
        'status',
    ];

    protected $hidden = ['created_at', 'completed_at', 'deleted_at', 'updated_at'];
    public function scopeActive(Builder $query): void
    {
        $query->where('state', '<>', -1);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }
    public function subscription()
    {
        return $this->hasMany(Subscription::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function discussion(){
        return $this->hasMany(CourseDiscussion::class);
    }
    public function userAccounts()
    {
        return $this->hasOne(User::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
    protected static function boot()
    {
        parent::boot();

        // Add a model event listener for deleting
        static::deleting(function ($customer) {
            // Delete related profiles, addresses, and user accounts
            $customer->profiles()->delete();
            $customer->addresses()->delete();
            $customer->userAccounts()->delete();
        });
    }
    public static function deleteIdentity($id)
    {
        $identity = self::findOrFail($id);
        // dd($identity);
        $identity->delete();
    }
    public static function createIdentity($data, $identity_id)
    {
        // dd($data);
        if ($identity_id) {
            $identity = self::find($identity_id);
            $identity->update($data);
        } else
            $identity = self::create($data);
        return $identity;
    }
    public static function updateIdentity($data, $identity_id)
    {
        $identity = self::where('id', $identity_id)->first();
        $identity->update($data);
        return $identity;
    }
    public static function getAll($numOfItems)
    {
        return  self::with(['profile', 'address'])->paginate($numOfItems);
    }
    public static function getOne($id)
    {
        $identity = self::where('id', $id)->with(['profile', 'address'])->first();
        return $identity;
    }
    public static function calculateAge($birth_date)
    {
        return Carbon::parse($birth_date)->age;
    }
}
