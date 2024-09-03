<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Services\GeoLocationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Agent\Agent;

class LoggedinDevices extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'device',
        'os',
        'browser',
        'location',
        'state'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public static function register($user_id, $ip){
        $agent = new Agent();
        // dd($agent);
        // $device = $agent->device();
        $isMobile = $agent->isMobile();
        $isTablet = $agent->isTablet();
        $isDesktop = $agent->isDesktop();
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);

        $data['user_id'] = $user_id;
        $data['device'] = $isMobile ? 'mobile':( $isDesktop ? 'desktop': ($isTablet? 'tablet': 'unkonwn'));
        $data['os']= $platform ? $platform.' '.$platformVersion : 'unknown';
        $data['browser'] = $browser ? $browser.' '.$browserVersion: 'unknown';

        $location = GeoLocationService::getGeoData($ip);
        $data['location'] = $location;
        return self::create($data);
    }

    public static function getMyDevices($user_id){
        return self::where('user_id',$user_id)->where('state','active')->get();
    }
    public static function logoutFromAllOtherDevices($user_id, $deviceId){
        $response = self::where('user_id',$user_id)->where('id','!=', $deviceId)->update(['state'=>'closed']);
    }
    public static function logoutDevice($deviceId){
        $data['state'] = 'closed';
        $device = self::find($deviceId);
        // dd($data);
        $device->update($data);
        // dd($device);
        return;
    }
}
