<?php
namespace App\Services;

use App\Models\CourseEnrollment;
use App\Models\Subscription;

class CourseEnrollmentService 
{
    public static function registerEnrollment($data){
        $permission = self::checkEnrollmentPermission($data['subscription_id']);
        if($permission['error']){
            throw new \Exception($permission['message'], 400);
        }
        $enrollment = CourseEnrollment::registerEnrollment($data);
        if($enrollment) {
            $newData['enrolled_courses'] =  $permission['subs']->enrolled_courses+1;
            Subscription::updateSubscription($newData, $permission['subs']->id );
        }
        return $enrollment;
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