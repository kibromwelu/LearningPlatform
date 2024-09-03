<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Foundation\Auth\User as Authenticatable;

// class User extends 

class Credential extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $fillable = [
        'identity_id',
        'password'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public static function validatePassword($request){

        $user = Credential::find($request->identity_id);
        dd($user);
        if ($user && Hash::check($request->password, $user->password)) {
            $token = Auth::fromUser($user);
            return $token;
        }
    }
    public static function register($data){
        // dd($data);
        $user = self::create($data);
        $token = Auth::fromUser($user);
        return $token;
    }
    public static function login(array $credentials)
    {
        $token =  Auth::attempt($credentials);
        if (! $token) {
            return ['message' => 'Invalid credentials', 'error'=>true];
        }
        $user = auth()->user();
        return [
            'access_token' => $token,
            'user'=> $user,
            'token_type' => 'Bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
