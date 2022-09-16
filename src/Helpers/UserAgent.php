<?php


namespace Ramaaz\MicroContact\Helpers;


use AliSuliman\MicroFeatures\Facades\Auth;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class UserAgent
{
    public static function createUserActivityRequest($data)
    {
        $agent = new Agent();
        return [
            'user_id' => Auth::id(),
            'activity_type' => $data['activity_type'],
            'jsonRequest' => $data['jsonRequest'],
            'jsonResponse' => $data['jsonResponse'],
            'device' => $agent->device(),
            'device_type' => $agent->deviceType(),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
            'browser' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'ip_address' => request()->ip(),
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
    }
}