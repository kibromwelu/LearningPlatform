<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Services\SubscriptionService;
// use App\Services\GeolocationService;
// use GuzzleHttp\Psr7\Message;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
        // return response()->json(['location' => $location]);
        // dd($location);
        $subscriptions = Subscription::all();
       return response()->json(['error'=>false, 'items'=>$subscriptions],200);
    // }/
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $subscription = SubscriptionService::subscribe($request);
        return response()->json(['error'=>false, 'message'=>'Subscription created successfully.','item'=>$subscription], 201);
    }

    public function addLearnersToMySubscription(Request $request){
    //    dd( $request->all());
        $subscriptionLearner = SubscriptionService::addLearnersToMySubscription($request);
        return response()->json(['error'=>false, 'message'=>"You have added a learner to your subscription.", 'data'=>$subscriptionLearner],201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $identity_id)
    {
        
        $subscription = Subscription::getMySubscription($identity_id);
        return response()->json(['error'=>false, 'items' => $subscription]);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, $identity_id)
    {
        
        $subscription = SubscriptionService::updateSubscription($request, $identity_id);
        return response()->json(['error'=>false, 'message' => "Subscription updated successfully", "item" => $subscription]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subscription_id)
    {
        //
       $subsc = Subscription::deleteSubscription($subscription_id);
    //    dd($subsc);
       return response()->json(['error'=>false, 'message'=> 'Subscription deleted'],202);
        
    }

    public function getMySubscriptions(){
        $user = auth()->user();
         $subscriptions = Subscription::getMySubscriptions($user->identity_id);
         return response()->json(['error'=>false, 'message'=>'success', 'data'=>$subscriptions]);
         dd($subscriptions);
    }

    public function getInvitedLearners($subscription_id){

        $response =  Subscription::getInvitedLearners($subscription_id);
        return response()->json(['error'=>false, 'message'=>'success', 'data'=>$response]);
        dd($subscription_id);
    }

    public function removeLearners($subscription_id, Request $request){
        SubscriptionService::removeLearnerFromSubs($subscription_id, $request->learner_id);
        return response()->json(['error'=>false, 'message'=>"You have removed the learner from your package"],202);
    }
}
