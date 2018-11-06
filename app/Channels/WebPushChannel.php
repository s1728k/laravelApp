<?php

namespace App\Channels;

use Illuminate\Support\Str;
use Minishlink\WebPush\WebPush;
use Illuminate\Notifications\Notification;

class WebPushChannel
{
    /** @var \Minishlink\WebPush\WebPush */
    protected $webPush;

    /**
     * @param  \Minishlink\WebPush\WebPush $webPush
     * @return void
     */
    public function __construct(WebPush $webPush)
    {
        // $this->webPush = $webPush($this->webPushConfig());
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $sub = json_decode($notifiable->subscription, true);

        $payload = json_encode($notification->toWebPush($notifiable, $notification)->toArray());

        $webPush = new WebPush($this->webPushConfig());

        \Log::Info($payload);
        \Log::Info($sub['keys']['p256dh']);
        \Log::Info($sub['keys']['auth']);

        $webPush->sendNotification($sub['endpoint'], "hi Welcome to honeyweb.org", $sub['keys']['p256dh'], $sub['keys']['auth'], true);

        $response = $webPush->flush();
        \Log::Info($response);
        // if ($response !== 1) {
        //     $notifiable->delete();
        // }
    }

    /**
     * @param  array|bool $response
     * @param  \Illuminate\Database\Eloquent\Collection $subscriptions
     * @return void
     */
    protected function deleteInvalidSubscriptions($response, $subscriptions)
    {
        if (! is_array($response)) {
            return;
        }

        foreach ($response as $index => $value) {
            if (! $value['success'] && isset($subscriptions[$index])) {
                $subscriptions[$index]->delete();
            }
        }
    }

    protected function webPushConfig()
    {
        $config = [];
        $webpush = config('webpush');
        $publicKey = $webpush['vapid']['public_key'];
        $privateKey = $webpush['vapid']['private_key'];

        if (! empty($webpush['gcm']['key'])) {
            $config['GCM'] = $webpush['gcm']['key'];
        }

        if (empty($publicKey) || empty($privateKey)) {
            return $config;
        }

        $config['VAPID'] = compact('publicKey', 'privateKey');
        $config['VAPID']['subject'] = $webpush['vapid']['subject'];

        if (empty($config['VAPID']['subject'])) {
            $config['VAPID']['subject'] = url('/');
        }

        if (! empty($webpush['vapid']['pem_file'])) {
            $config['VAPID']['pemFile'] = $webpush['vapid']['pem_file'];

            if (Str::startsWith($config['VAPID']['pemFile'], 'storage')) {
                $config['VAPID']['pemFile'] = base_path($config['VAPID']['pemFile']);
            }
        }

        return $config;
    }
}
