<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PasswordResetLink extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['email', 'user_id', 'token', 'expires_at'];

    public static function resetPassword($data, $token)
    {
        $tokenData = PasswordResetLink::where('token', $token)->first();
        if (!$tokenData || $tokenData->expires_at < now()) {
            throw new Exception('Expired link');
        }
        $user = User::where('identity_id', $tokenData->user_id)->first();
        $user->password = $data['password'];
        DB::beginTransaction();
        try {
            $tokenData->delete();
            $user->save();
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            throw new Exception('Something went wrong');
        }
    }
}
