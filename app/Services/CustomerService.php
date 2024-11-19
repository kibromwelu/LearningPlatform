<?php
namespace App\Services;
use Illuminate\Http\Request;
use App\Models\Identity;
use App\Models\LoggedinDevices;
use App\Models\Address;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\RegistrationStep4Request;
use App\Services\GeolocationService;

use Exception;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    // public static function registerCustomer(Request $request)
    // {
    //     return DB::transaction(function () use ($request) {
    //             $identityData = $request->identity;
    //             $profileData = $request->profile;
    //             $addressData = $request->address;
    //             $credential = $request->user;
    //             $identity = Identity::createIdentity($identityData);
    //             // Add identity id to customer details
    //             if($addressData){
    //                 $addressData['identity_id'] = $identity->id;
    //                 Address::register($addressData);
    //             }

    //             if($profileData){
    //                 $profileData['identity_id'] = $identity->id;
    //                 Profile::register($profileData);
    //             }
    //             if($credential){
    //                 $credential['identity_id'] = $identity->id;                    
    //                 $credential = User::registerUser($credential);
    //             }

    //             return $credential;
    //         });
    // }

     public static function postStep1(Request $request)
    {
        
        $identity_id = $request->identity_id;
        $data=$request->validated();
        $identity = Identity::createIdentity($data, $identity_id);
        
        self::completeStep($identity->id, 1);
        return $identity;
        return response()->json(['message' => 'Step 1 completed successfully.']);
    }

    public static function postStep2(Request $request, $identity_id)
    {
        $data = $request->validated();
        $data['identity_id'] = $identity_id ;
       
        $address = Address::register($data);

        $message = self::completeStep($identity_id, 2);
        return $address;
    }

    public static function postStep3(Request $request, $identity_id){
        $data = $request->all();
        Log::info($data);

        $data['identity_id'] = $identity_id;
        $identity = Identity::updateIdentity($data,$identity_id);
        
        $profileData['age'] = self::calculateAge($data['birth_date']);
        $profileData['identity_id'] = $identity_id;
        $ip = $request->ip();
        $location = GeolocationService::getGeoData($ip);
        $profileData['isoCode']=$location;
        Profile::register($profileData);

        if($identity)  self::completeStep($identity_id, 3);
        return $identity;
    }

    public static function postStep4(Request $request, $identity_id)
    {             
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
        $identity = Profile::register($data);
        self::completeStep($identity_id, 4);
        return $identity;
        
    }
    public static function postStep5(Request $request, $identity_id)
    {
        $data = $request->all();
        Log::info($data);
        $data['identity_id'] = $identity_id ;
        $user = User::registerUser($data);
        // $logDevice = LoggedinDevices::register($user['user']->id, $request->ip());

        self::completeStep($identity_id, 5);

        $identity = Identity::getone($identity_id);
        $formatted_data['identity_id'] = $identity->id;
        $formatted_data['name'] = $identity->first_name.' '.$identity->last_name;
        $formatted_data['username'] = $user[0]['username'];
        $formatted_data['email'] = $identity->address->email;
        if($identity->profile && $identity->profile->avatar)
            $formatted_data['avatar'] = url('/').'/api/auth/profile-pic/'.$identity->profile->avatar;
        $user[2] = $formatted_data;
        // $user[3] = $logDevice;
        return $user;
    }
   static function calculateAge($birthdate) {
        // Convert the birthdate to a Carbon instance
        $dob = Carbon::parse($birthdate);
        return $dob->age;
    }
    public static function completeStep($identity_id, $stepNumber)
    {
        // Retrieve the registration record
        $identity = Identity::where('id',$identity_id)->first();
        // Decode the existing completed_steps or initialize it as an empty array
        $completedSteps = $identity->completed_steps ? json_decode($identity->completed_steps, true) : [];
        // Check if the step is already completed
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
        $requiredSteps = [1, 2, 3,5]; 

        // Check if all required steps are completed
        $completedSteps = json_decode($identity->completed_steps, true);
        if (empty(array_diff($requiredSteps, $completedSteps))) {
            $identity->completed_at = now();
            $identity->save();
            return $identity;
        }
    }
}