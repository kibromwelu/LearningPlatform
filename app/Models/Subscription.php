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

    public function enrollment()
    {
        return $this->hasMany(CourseEnrollment::class);
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

    public static function updateSubscription($data,$id){
        $subscription = self::find($id);
        $subscription->update($data);
        return $subscription;
    }

    public static function getMySubscriptions($identity_id){
        return self::where('identity_id', $identity_id)->get();
    }

    public static function deleteSubscription($identity_id){
        $subscription = self::where('identity_id',$identity_id);
        $subscription->delete();
        return response()->json(['message' => 'You have successfully unsubscribed the package']);
    }

    public static function getOne($id){
        return self::where('id',$id)->first();
    }

}
