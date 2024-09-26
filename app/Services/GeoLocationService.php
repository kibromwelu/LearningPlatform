<?php 

namespace App\Services;

use Illuminate\Support\Facades\DB; // Use Laravel's DB facade if this is your intention
use GeoIp2\Database\Reader;
class GeoLocationService
{
    public static function getGeoData($ipAddress)
    {
       
        $ipAddress = '8.8.8.8';
        $databasePath = storage_path('app/GeoLite2-Country.mmdb');
        
        $reader = new Reader($databasePath);
        $record = $reader->country($ipAddress);
        return $record->country->isoCode;
    }
}
