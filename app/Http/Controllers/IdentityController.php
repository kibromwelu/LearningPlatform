<?php

namespace App\Http\Controllers;

use App\Models\Identity;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Http\Requests\StoreIdentityRequest;
use App\Http\Requests\RegistrationStep1Request;
use App\Http\Requests\RegistrationStep2Request;
use App\Http\Requests\RegistrationStep3Request;
use App\Http\Requests\RegistrationStep4Request;
use App\Http\Requests\RegistrationStep5Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateIdentityRequest;
class IdentityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        



        return Identity::getAll($request->numOfItems);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdentityRequest $request)
    {
        // dd($request->all());
       
        // $response = CustomerService::registerCustomer($request);
        // return response()->json(['error'=> false, 'message'=> 'registered successfully', 'token'=>$response], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($identity_id)
    {
        // dd($identity);
        $identity = Identity::getOne($identity_id);
        
        return response()->json(['error'=>false, "data"=>$identity, 'message'=>'success'],200);
    }
   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdentityRequest $request, $identity)
    {
        //
        $identity = Identity::updateIdentity($request->all(), $identity);
        return response()->json(['error'=>false, 'data'=>$identity, "message" => "updated SuccessFully"], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($identity_id)
    {
        Identity::deleteIdentity($identity_id);
        return response()->json(['error'=>false, 'message'=>"Customer deleted."]);
    }
  
    
    public function postStep1(RegistrationStep1Request $request){
        $response =  CustomerService::postStep1($request);
        return response()->json(['error'=>false, "message"=>"First step completed", 'data'=>$response], 201);
    }

    public function postStep2(RegistrationStep2Request $request,$identity_id){

        $response = CustomerService::postStep2($request,$identity_id);
        return response()->json(['error'=>false, "message"=>"Second step completed", 'data'=>$response], 201);
    }

    public function postStep3(RegistrationStep3Request $request, $identity_id){
        $response = CustomerService::postStep3($request,$identity_id);
        return response()->json(['error'=>false, "message"=>"Third step Completed",'data'=>$response], 202);
    }

    public function postStep4(RegistrationStep4Request $request, $identity_id){
        $response = CustomerService::postStep4($request,$identity_id);
        return response()->json(['error'=>false, "message"=>"You are near to complete", 'data'=>$response],202);
    }
    public function postStep5(RegistrationStep5Request $request, $identity_id){
        $user = CustomerService::postStep5($request,$identity_id);
        return response()->json(['error'=>false,'access_token'=>$user[1], 'user'=>$user[2], 'message'=>'You have registered successfully!',]);
    }
}
