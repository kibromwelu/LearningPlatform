<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Subscription extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    protected $fillable = [
        'identity_id',
        'package',
        'mode',
        'payment',
        'currency',
        'max_allowed_learners',
        'max_allowed_courses',
        'added_learners',
        'enrolled_courses',
        'subscription_id',
        'state'
    ];


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscription_id');
    }
    public function identity()
    {
        return $this->belongsTo(Identity::class, 'identity_id');
    }

    // Boot method to set up model event handling
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($subscription) {
            // Delete all child subscriptions before deleting the parent subscription
            $subscription->subscriptions()->each(function ($child) {
                $child->delete();
            });
        });
    }

    public static function register($data)
    {
        if (isset($data['subscription_id'])) {
            $user = Auth()->user();
            // $subscription = Subscription::find($data['subscription_id']);
        };
        if (isset($data['subscription_id']) && Subscription::where('identity_id', $data['identity_id'])->where('subscription_id', $data['subscription_id'])->exists()) {
            throw new \Exception("Learner is already invited.", 400);
        } else if (isset($data['subscription_id']) && $data['identity_id'] == $user->identity_id) {
            throw new \Exception("you can't invite to your self.", 400);
        } else {
            $subscription = self::create($data);
            if (isset($data['subscription_id'])) {
                $parent = Subscription::find($data['subscription_id']);
                $newData['added_learners'] = $parent->added_learners + 1;
                $parent->update($newData);
            }
            return $subscription;
        }
    }



    public static function getInvitedLearners($subscription_id)
    {

        return self::where('subscription_id', $subscription_id)
            ->with('identity:id,first_name,last_name')->select('id', 'subscription_id', 'identity_id')->get();
    }
    public static function updateSubscription($data, $id)
    {

        $subscription = self::find($id);
        $subscription->update($data);
        return $subscription;
    }

    public static function getMySubscriptions($identity_id)
    {
        return self::where('identity_id', $identity_id)->where('state', '!=', 'canceled')->get();
    }

    public static function deleteSubscription($id)
    {
        $subs = self::find($id);
        if ($subs->state == 'approved') {
            throw new \Exception("You can't remove the learner, he/she is already approved", 400);
        }
        return $subs->delete();
    }
    public static function removeLearnerFromSubscription($subscription_id, $learner_id)
    {
        $subscription = self::where('subscription_id', $subscription_id)->where('identity_id', $learner_id)->first();
        if (!$subscription) {
            throw new \Exception("subscription not found", 404);
        } else if ($subscription->state == 'approved') {
            throw new \Exception("You can't remove the learner, he/she is already approved", 400);
        }
        $subscription->delete();
        return response()->json(['message' => 'You have successfully removed the learner from the package']);
    }
    public static function getOne($id)
    {
        return self::where('id', $id)->first();
    }
    public static function getAll($state = null)
    {
        $state = request()->query('state');  // Get 'state' from query parameters
        $query = self::query();
        if ($state) {
            $query->where('state', $state);
        }
        $query->with('ceo', 'accountant');
        return $query->get();
    }

    public static function manageSubscription($data)
    {
        $user = Auth()->user();
        $time = Carbon::now();
        $sign = Signature::where('identity_id', $user->identity_id)->where('state', 'active')->first();

        Log::info($data['subscriptionIds']);
        $examRequests = self::whereIn('id', $data['subscriptionIds'])->get();
        foreach ($examRequests as $request) {
            if ($data['state'] == 'approve') {
                $request->update(['state' => 'approved', 'clo_id' => $user->identity_id, 'accountant_action' => 'approve', 'accountant_action_date' => $time, 'accountant_sign_id' => $sign->id]);
            } elseif ($data['state'] == 'authorize') {
                $request->update(['state' => 'authorized', 'ceo_id' => $user->identity_id, 'ceo_action' => 'authorize', 'ceo_action_date' => $time, 'ceo_sign_id' => $sign->id]);
            } elseif ($data['state'] == 'ceo-reject') {
                $request->update(['state' => 'ceo-rejected', 'ceo_id' => $user->identity_id, 'ceo_action' => 'reject', 'ceo_action_date' => $time, 'ceo_sign_id' => $sign->id]);
            } elseif ($data['state'] == 'accountant-reject') {
                $request->update(['state' => 'accountant-rejected', 'accountant_id' => $user->identity_id, 'accountant_action' => 'reject', 'accountant_action_date' => $time, 'accountant_sign_id' => $sign->id]);
            }
        }
        return $examRequests;
    }
}
