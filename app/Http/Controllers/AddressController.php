<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Address;
use App\Http\Requests\StoreaddressRequest;
use App\Http\Requests\UpdateaddressRequest;

class AddressController extends Controller
{
    public function checkEmailOrPhone(Request $request)
    {
        $user = Address::where('email', $request->email_or_phone)
                    ->orWhere('mobile_number', $request->email_or_phone)
                    ->first();

        if ($user) {
            return response()->json([
                'message' => 'User found',
                'user_id' => $user->identity_id // return user ID to validate the password next
            ]);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
    
    public function show($identityId)
    {
        // dd($identityId);
        $userAddress = Address::getMyAddress($identityId);
        return response()->json(['error: '=>false, 'message'=>'success', 'data'=>$userAddress], 200);
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateaddressRequest $request, $identityId)
    {
        // dd($request);
        $userAddress = Address::updateAddress($request->all(), $identityId);
        return response()->json(['error'=>false, 'message'=>'updated successfuly', 'item'=>$userAddress]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(address $address)
    {
        //
    }
}
