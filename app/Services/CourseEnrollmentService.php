<?php
namespace App\Services;

use App\Models\CourseEnrollment;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class CourseEnrollmentService 
{
    public static function registerEnrollment($data){

        $permission = self::checkEnrollmentPermission($data['subscription_id']);

        if($permission['error']){
            throw new \Exception($permission['message'], 400);
        }
        
        DB::beginTransaction();
        try {
             $enrollment = CourseEnrollment::registerEnrollment($data);
            if($enrollment) {
                $newData['enrolled_courses'] =  $permission['subs']->enrolled_courses+1;
                Subscription::updateSubscription($newData, $permission['subs']->id );
            }
            DB::commit();
            return $enrollment;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception('Something went wrong'.$th, 400);

        }
       
    }

    public static function removeEnrollmentFromSubscription($courseEnrollmentId){
        // fetch enrollment
        $enrollment = CourseEnrollment::getOne($courseEnrollmentId);
        // delete enrollment 
        $removedLearner = CourseEnrollment::deleteEnrollment($courseEnrollmentId);
        // fetch subscription
        $subs = Subscription::getOne($enrollment->subscription_id);
        if($removedLearner){
            // decrement enrolled courses in subscription
            $data['enrolled_courses'] = $subs->enrolled_courses - 1;
        }
        // update supscription
        return Subscription::updateSubscription($data, $enrollment->subscription_id);

    }

    public static function checkEnrollmentPermission($subscriptionId){
        $subs = Subscription::getOne($subscriptionId);
        if(!$subs){
            return [
                'error'=>true,
                'message'=>'You have reached max allowed enrollment for this subscription.'
            ]; 
        }
        else if($subs->max_allowed_courses <= $subs->enrolled_courses){
            return [
                'error'=>true,
                'message'=>'You have reached max allowed enrollment for this subscription.'
            ];
        }
        return [
            'error'=>false, 
            'subs'=>$subs
        ];
    }

}