<?php


namespace AliSuliman\MicroFeatures\Services\Services;

use AliSuliman\MicroFeatures\RemoteModels\FcmToken;

class Notifying
{
    public static function addFcmToken($user_id, $fcm_token)
    {
        return FcmToken::query()->store([
            'user_id' => $user_id,
            'fcm_token' => $fcm_token,
        ]);
    }

    public static function removeFcmToken($fcm_token)
    {
        return FcmToken::query()->where('fcm_token','=',$fcm_token)->delete();
    }

    public static function send($user_id, $role_id, $notification_type_id, $variables, $extra_fields = [])
    {
        return rpc(config('microservices.notifications'),'notifications', 'send', [
            'users' => [
                [
                    'user_id' => $user_id,
                    'role_id' => $role_id,
                ]
            ],
            'notification_type_id' => $notification_type_id,
            'variables' => $variables,
            'extra_fields' => $extra_fields
        ]);
    }

    public static function sendToMany($users_with_roles, $notification_type_id, $variables, $extra_fields = [])
    {
        return rpc(config('microservices.notifications'),'notifications', 'send', [
            'users' => $users_with_roles,
            'notification_type_id' => $notification_type_id,
            'variables' => $variables,
            'extra_fields' => $extra_fields
        ]);
    }

}