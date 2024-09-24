<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Services\GeoLocationservice;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Jenssegers\Agent\Agent;
class User extends Authenticatable implements JWTSubject
{
    use HasFactory,Notifiable;
    use HasUuids;

    protected $fillable = [
        'identity_id',
        'username',
        'password',
        'role',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public static function getUsers($num){
        return self::paginate(20);
    }

    public static function updateUser($data,$id){
        $newData = [
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'role' => $data['role'] ?? null,
            'password' => isset($data['password']) ? Hash::make($data['password']) : null,
        ];

        $user = self::findOrFail($id);
        $user->update($newData);
        return $user;
        
    }

    public static function registerUser($data)
    {
    
        $user = self::updateOrCreate(['identity_id'=>$data['identity_id']],$data);
        $token = Auth::fromUser($user);
        
        return [$user, $token];
    }
    public static function deleteUser($id)
    {
        $user = self::findOrFail($id);
        $user->delete();
        return response()->json(['message'=>"Item Deleted"]);
    }
    public static function login(array $credentials)
    {
        $token =  Auth::attempt($credentials);
        if (! $token) {
            return ['message' => 'Invalid credentials', 'error'=>true];
        }
        $user = auth()->user();
        $userDetail = Identity::getOne($user->identity_id);
        if($userDetail->profile && $userDetail->profile->avatar)
        $profile_link = url('/').'/api/auth/profile-pic/'.$userDetail->profile->avatar;
        else{
            $profile_link = 'not found';
        }
        return [
            'access_token' => $token,
            'user'=> $user,
            'profile_link'=>$profile_link,
            'token_type' => 'Bearer',
            'expires_in' => Auth::factory()->getTTL() * 3600,
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