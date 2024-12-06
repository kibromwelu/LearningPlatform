<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Services\GeoLocationservice;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
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
    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    public function identity(){
        return $this->belongsTo(Identity::class)->select('first_name', 'last_name', 'id');
    }
    public static function forgotPassword(Request $request)
    {
        $token = Str::random(60);
        $user = Address::where('email', $request->email)->first();

        if (!$user || !$user->identity_id) {
            throw new Exception('Invalid email address');
        }
        $userRole = self::where('identity_id', $user->identity_id)->first();
        if ($userRole->role == 'user') {
            $tokenData['email'] = $request->email;
            $tokenData['user_id'] = $user->identity_id;
            $tokenData['token'] = $token;
            $tokenData['expires_at'] = now()->addMinutes(20); // Set expiration to 20 minutes
            PasswordResetLink::create($tokenData);
            ///while integrating --- send the route link below via email and return "Link sent to your email",// since email is not working I am returning the link instead of success me
            return ['error' => false, 'message' => 'We have sent you an email verification link. Please go to your email and verify it.', 'link' => route('secure.route', ['token' => $token])];
        }
        $data['email'] = $request->email;
        PasswordResetRequest::store($data);
        return ['error' => false, 'message' => 'reset request sent successfully. contact your admin.', "link" => null];
    }
    public static function getUsers($num)
    {
        return self::with('identity')->paginate(20);
    }

    public static function updateUser($data, $id)
    {

        $user = self::where('')->first();
        $user->update($data);
        return $user;
    }

    public static function registerUser($data)
    {

        $user = self::updateOrCreate(['identity_id' => $data['identity_id']], $data);
        $token = Auth::fromUser($user);

        return [$user, $token];
    }
    public static function deleteUser($id)
    {
        $user = self::findOrFail($id);
        $user->delete();
        return response()->json(['message' => "Item Deleted"]);
    }
    public static function login(array $credentials)
    {
        $token =  Auth::attempt($credentials);
        if (! $token) {
            return ['message' => 'Invalid credentials', 'error' => true];
        }
        $user = auth()->user();
        $userDetail = Identity::getOne($user->identity_id);
        if ($userDetail->profile && $userDetail->profile->avatar)
            $profile_link = url('/') . '/api/auth/profile-pic/' . $userDetail->profile->avatar;
        else {
            $profile_link = 'not found';
        }
        return [
            'access_token' => $token,
            'user' => $user,
            'profile_link' => $profile_link,
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
