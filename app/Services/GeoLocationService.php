<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB; // Use Laravel's DB facade if this is your intention
use GeoIp2\Database\Reader;
class GeoLocationService
{
    public static function getGeoData($ipAddress)
    {
        //  $ipAddress = '196.189.12.2';
         $ipAddress = '8.8.8.8';
        $databasePath = storage_path('app/GeoLite2-Country.mmdb');
        // dd($databasePath);
        // Create a GeoIP2 Reader instance
        $reader = new Reader($databasePath);
        // dd($reader);
        // Get location data based on the IP address
        $record = $reader->country($ipAddress);
        return $record->country->isoCode;
        // return response()->json([
        //     'ip' => $ipAddress,
        //     'country' => $record->country->name,
        //     'isoCode' => $record->country->isoCode,
        // ]);
    }
}
