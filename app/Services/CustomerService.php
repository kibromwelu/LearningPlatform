<?php
namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Identity;
use App\Models\Address;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegistrationStep4Request;
use App\Services\GeolocationService;

use Exception;
class CustomerService
{
    public static function registerCustomer(Request $request)
    {
        return DB::transaction(function () use ($request) {
                // Get relevant request data
                // dd($request->identity);
                $identityData = $request->identity;
                $profileData = $request->profile;
                $addressData = $request->address;
                $credential = $request->user;
                //  Create Identity
                $identity = Identity::createIdentity($identityData);
                // Add identity id to customer details
                if($addressData){
                    $addressData['identity_id'] = $identity->id;
                    Address::register($addressData);
                }

                if($profileData){
                    $profileData['identity_id'] = $identity->id;
                    Profile::register($profileData);
                }
                if($credential){
                    $credential['identity_id'] = $identity->id;                    
                    $credential = User::registerUser($credential);
                }

                return $credential;
            });
    }

     public static function postStep1(Request $request)
    {
        
        $ip = $request->ip();
        // dd($ip);
        $data=$request->only(['first_name','last_name']);
        $location = GeolocationService::getGeoData($ip);
        $data['isoCode']=$location;
        // dd($data);
        $identity = Identity::createIdentity($data);
        
        $message = self::completeStep($identity->id, 1);
        return $identity;
        return response()->json(['message' => 'Step 1 completed successfully.']);
    }

    public static function postStep2(Request $request, $identity_id)
    {
        $data = $request->all();
        $data['identity_id'] = $identity_id ;
        $address = Address::register($data);
        $message = self::completeStep($identity_id, 2);
        return $address;
    }

    public static function postStep3(Request $request, $identity_id){
        $data = $request->all();
        $data['identity_id'] = $identity_id;
        $identity = Identity::updateIdentity($data,$identity_id);
        if($identity) $message = self::completeStep($identity_id, 3);
        return $identity;
    }

    public static function postStep4(Request $request, $identity_id)
    {
        // dd($request->all());
        
        $data = [
            'identity_id' => $identity_id,
        ];
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverFileName = time() . '_cover_' . $cover->getClientOriginalName();
            $cover->storeAs('uploads/covers', $coverFileName, 'public');
            $data['cover'] = $coverFileName;
        }
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $fileName = time() . '_profile_' . $file->getClientOriginalName();
            $file->storeAs('uploads/profiles', $fileName, 'public');
            $data['avatar'] = $fileName;
        }
        // dd($data);
        $identity = Profile::register($data);
        $message = self::completeStep($identity_id, 4);
        return $identity;
        
    }
    public static function postStep5(Request $request, $identity_id)
    {
        $data = $request->all();
        $data['identity_id'] = $identity_id ;
        // dd($data);
        $identity = User::registerUser($data);
        // dd($identity);
        self::completeStep($identity_id, 5);
        return $identity;
        
    }

    public static function completeStep($identity_id, $stepNumber)
    {
        // Retrieve the registration record
        // dd($identity_id);
        $identity = Identity::where('id',$identity_id)->first();
        // dd($identity);
        // Decode the existing completed_steps or initialize it as an empty array
        $completedSteps = $identity->completed_steps ? json_decode($identity->completed_steps, true) : [];
        // Check if the step is already completed
        // dd($completedSteps);
        if (!in_array($stepNumber, $completedSteps)) {
            // Add the new step to the array
            $completedSteps[] = $stepNumber;
            // Update the completed_steps in the database
            $identity->completed_steps = json_encode($completedSteps);
            $identity->save();
            // Check if the idenity$identity is complete
            self::checkAndCompleteRegistration($identity);
        }
        return response()->json(['message' => 'Step completed successfully']);
    }
    protected static function checkAndCompleteRegistration(Identity $identity)
    {
        // Define the required steps
        $requiredSteps = [1, 2, 3, 4,5]; // Adjust this list based on your steps

        // Check if all required steps are completed
        $completedSteps = json_decode($identity->completed_steps, true);
        if (empty(array_diff($requiredSteps, $completedSteps))) {
            // All steps completed, update completed_at
            $identity->completed_at = now();
            $identity->save();
            return $identity;
        }
    }
}