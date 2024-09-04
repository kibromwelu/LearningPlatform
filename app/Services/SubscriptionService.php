<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Constants\CostRules;
use App\Models\Identity;
use App\Models\Subscription;
use Exception;
class SubscriptionService
{
    public static function subscribe(Request $request)
    {
        
        $data = $request->all();
        $package = $request->package;
        $paymentMode = $request->mode;

        $user = Identity::find($request->identity_id);
        $currencyType = $user->isoCode == 'ET' ? 'ETB' : "USD";
        $data['currency'] = $currencyType;
        if (!isset(CostRules::PACKAGES[$package]['pricing'][$paymentMode][$currencyType])) {
            return response()->json(['error' => 'Invalid package or payment time range'], 400);
        }
        $data['max_allowed_learners'] = CostRules::PACKAGES[$package]['max_allowed_learners'];
        $data['max_allowed_courses'] = CostRules::PACKAGES[$package]['max_courses'];
        $data['payment'] = CostRules::PACKAGES[$package]['pricing'][$paymentMode][$currencyType];
    //    dd($data);
        return Subscription::register($data);
    }

    public static function removeLearnerFromSubs($subscription_id, $learner_id){
        Subscription::deleteSubscription($subscription_id, $learner_id);
        $subscription = Subscription::getOne($subscription_id);
        if($subscription){
            $newData['added_learners'] = $subscription->added_learners - 1;
            
        }
    }

    public static function addLearnersToMySubscription(Request $request){
        $user = auth()->user();
        $canAdd = self::canAddLearner($user->identity_id, $request->subscription_id);
        if($canAdd['error']==true){
            throw new \Exception($canAdd['message'], 400);
        }else{
            $data = $canAdd['subs']->only('package', 'mode', 'currency', 'payment');
            $data['identity_id'] = $request->identity_id;
            
            $data['max_allowed_learners'] = 0;
            $data['max_allowed_courses'] = $canAdd['subs']->max_allowed_courses;
            $data['subscription_id'] = $canAdd['subs']->id;
        }

        return Subscription::register($data);

    }
    public static function canAddLearner($identity_id, $subscription_id){
        $subs = Subscription::getOne($subscription_id);
        if(!$subs || $subs->identity_id != $identity_id){
            return [
                'error'=> true,
                'message'=>'You are not allowed to add learner to this subs.'
            ];
        }else if($subs->subscription_id){
            return [
                'error'=> true,
                'message'=>'You have no permission to invite friends to this package.'
            ];
        }
        else if($subs->max_allowed_learners <= $subs->added_learners){
            return [
                'error'=> true,
                'message'=>'You have reached your max invitation permissions to this package.'
            ];
        }
        return [
            'error'=>false,
            'subs'=>$subs
        ];
    }
    public static function updateSubscription(Request $request, $identity_id){
        $data = $request->all();
        $package = $request->package;
        $paymentMode = $request->mode;
        $currencyType = $request->currency;
        if (!isset(CostRules::PACKAGES[$package]['pricing'][$paymentMode][$currencyType])) {
            return response()->json(['error' => 'Invalid package or payment time range'], 400);
        }
        // Find the cost option that matches the currency type
        $data['payment'] = CostRules::PACKAGES[$package]['pricing'][$paymentMode][$currencyType];;
        return Subscription::updateSubscription($data, $identity_id);
        
    }
}