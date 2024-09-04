<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public function identity()
{
    return $this->belongsTo(Identity::class, 'identity_id');
}
    public function enrollment()
    {
        return $this->hasMany(CourseEnrollment::class);
    } 
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id'); 
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscription_id'); 
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

    public static function register($data){
        // dd($data);
        $subscription = self::create($data);
        if(isset($data['subscription_id'])){
            $parent = Subscription::find($data['subscription_id']);
            $newData['added_learners'] = $parent->added_learners + 1;
            $parent->update($newData);
        }
        return $subscription;
    }

    

    public static function getInvitedLearners($subscription_id){
    //    return self::where('subscription_id', $subscription_id)
    // ->get();
    // dd('AM I HERE');
   return self::where('subscription_id', $subscription_id)
                ->with('identity:id,first_name,last_name')->select('id','identity_id')->get();
    }
    public static function updateSubscription($data,$id){
        $subscription = self::find($id);
        $subscription->update($data);
        return $subscription;
    }

    public static function getMySubscriptions($identity_id){
        return self::where('identity_id', $identity_id)->get();
    }

    public static function deleteSubscription($id){
        $subs = self::find($id);
        if($subs->state == 'approved'){
            throw new \Exception("You can remove the learner, he/she is already approved", 400);
        }
        return $subs->delete();
    }
    public static function removeLearnerFromSubscription($subscription_id, $learner_id){
        $subscription = self::where('subscription_id', $subscription_id)->where('identity_id', $learner_id)->first();
        if($subscription->state=='approved'){
            throw new \Exception("You can remove the learner, he/she is already approved", 400);
        }
        $subscription->delete();
        return response()->json(['message' => 'You have successfully removed the learner from the package']);
    }
    public static function getOne($id){
        return self::where('id',$id)->first();
    }

}
