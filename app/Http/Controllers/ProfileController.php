<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\profile;
use Illuminate\Http\Request;
use App\Http\Requests\StoreprofileRequest;
use App\Http\Requests\UpdateprofileRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   

    /**
     * Display the specified resource.
     */
   
    
    public function show($profile)
    {
        //
        // dd($profile);
        $userprofile = Profile::getMyProfile($profile);
        return response()->json(['error: '=>false, 'item: '=> $userprofile]);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $identityId)
    {
        // dd($request->all());
        $profile = Profile::updateProfile($request, $identityId);
        return response()->json(['error'=>false, 'message' => 'profile updated successfully', 'item' => $profile]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(profile $profile)
    {
        
    }

    public function getProfilePic($filename)
    {
        $filePath = 'uploads/profiles/' . $filename;
        // $profile = 
        if (Storage::disk('public')->exists($filePath)) {
            $fileContent = Storage::disk('public')->get($filePath);
            
            // Determine the mime type of the file
            $mimeType = Storage::disk('public')->mimeType($filePath);

            // Return the file content as a response
            return response($fileContent, 200)->header('Content-Type', $mimeType);
            
            // return Storage::disk('public')->download($filePath);//uncomment to download the image
        }
        return response()->json([
            'success' => false,
            'message' => 'File not found.',
        ], 404);
    }


}
