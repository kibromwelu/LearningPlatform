<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PasswordResetRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'identity_id',
        'state',
        'authorized_by',
        'reset_by'
    ];

    public function user()
    {
        return $this->belongsTo(Identity::class, 'identity_id')->select('id', 'first_name', 'last_name');
    }

    public function authorizedBy()
    {
        return $this->belongsTo(Identity::class,  'authorized_by');
    }
    public function resetBy()
    {
        return $this->belongsTo(Identity::class,  'reset_by');
    }

    public static function store($data)
    {
        $user = Address::where('email', $data['email'])->first();
        if ($user) {
            $data['identity_id'] = $user->identity_id;
            return self::create($data);
        } else {
            throw new Exception('No user found with this email address ' . $data['email'], 404);
        }
    }

    public static function getAll()
    {
        $state = request()->query('state');
        // dd($state);
        $query = self::query();

        if ($state) {
            $query->where('state', $state);
        }

        $query->with('user', 'authorizedBy', 'resetBy');
        // dd($query);
        return $query->get();
    }
    public static function updateResetLinks($data)
    {
        $userId = Auth()->user()->identity_id;
        foreach ($data['requestIds'] as $resetRequestId) {
            $request = self::findOrFail($resetRequestId);
            if ($data['state'] == 'authorize') {
                $request->update(['state' => 'authorized', 'authorized_by' => $userId]);
                return 'authorized';
            } else if ($data['state'] == 'reset') {
                $request->update(['state' => 'reset', 'reset_by' => $userId]);
                return 'reset';
            }
        }
    }

    public static function resetApprovedRequests($data)
    {
        $user = User::where('identity_id', $data['user_id'])->first();
        $user->update($data);
        // return self::updateResetLinks()
    }

    public static function resetPassword($data)
    {
        $request = self::find($data['id']);
        if ($request->state == 'reset') {
            throw new Exception('Request already reset.');
        }
        $user = User::where('identity_id', $request->identity_id)->first();
        DB::beginTransaction();
        try {
            $requestUpdateData = [
                'requestIds' => [
                    $request->id
                ],
                'state' => 'reset'
            ];
            self::updateResetLinks($requestUpdateData);

            $userData['password'] = $data['password'];
            $user->password = $data['password'];
            $user->save();
            DB::commit();
            return 'password reset successfully';
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('something went wrong ' . $th);
        }
    }
}
