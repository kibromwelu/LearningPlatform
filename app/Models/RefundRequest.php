<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefundRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'requested_by',
        'subscription_id',
        'state',
        'ceo_id',
        'ceo_action',
        'ceo_action_date',
        'accountant_id',
        'accountant_action',
        'accountant_action_date'
    ];
    public function requestedBy()
    {
        return $this->belongsTo(Identity::class, 'requested_by')->select('id', 'first_name', 'last_name');
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
    public function ceo()
    {
        return $this->belongsTo(Identity::class, 'ceo_id')->select('id', 'first_name', 'last_name');
    }
    public function accountant()
    {
        return $this->belongsTo(Identity::class, 'accountant_id')->select('id', 'first_name', 'last_name');
    }
    public static function store($data)
    {
        $data['requested_by'] = Auth()->user()->identity_id;
        return self::create($data);
    }

    public static function getAll()
    {
        $state = request()->query('state') ?? 'new';
        $query = self::query();
        if ($state) {
            $query->where('state', $state);
        }
        $query->with('requestedBy', 'subscription.identity', 'ceo', 'accountant');
        return  $query->get();
    }
    public static function updateRefund($data)
    {
        $userId = Auth()->user()->identity_id;
        $now = Carbon::now();
        foreach ($data['requestIds'] as $refundRequestId) {
            $request = self::findOrFail($refundRequestId);
            if ($data['state'] == 'approve') {
                $request->update(['state' => 'approved', 'accountant_id' => $userId, 'accountant_action' => 'approve', 'accountant_action_date' => $now]);
                return 'approved';
            } else if ($data['state'] == 'authorize') {
                $request->update(['state' => 'authorized', 'ceo_id' => $userId, 'ceo_action' => 'authorize', 'ceo_action_date' => $now]);
                $ddata['state'] = 'canceled';
                $req = Subscription::updateSubscription($ddata, $request->subscription_id);
                return 'authorized';
            } else if ($data['state'] == 'reject' && $request->accountant_id == null) {
                $request->update(['state' => 'acc-rejected', 'accountant_id' => $userId, 'accountant_action' => 'reject', 'accountant_action_date' => $now]);
                return 'rejected';
            } else if ($data['state'] == 'reject' &&  $request->accountant_id != null) {
                $request->update(['state' => 'ceo-rejected', 'ceo_id' => $userId, 'ceo_action' => 'reject', 'ceo_action_date' => $now]);
                return 'rejected';
            }
        }
    }

    public static function destroy($id)
    {
        $request = self::findOrFail($id);
        return $request->delete();
    }
}
