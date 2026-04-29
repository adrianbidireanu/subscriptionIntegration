<?php

namespace App\Service;

class SubscriptionService
{
    public function __construct(
        private CarrierApiClient $api
    ) {
    }

    public function sendPin(string $phone): array
    {
        $res = $this->api->checkSubscription($phone);

        if (($res['status'] ?? null) === 'ALREADY_SUBSCRIBED') {
            return ['step' => 'subscribed'];
        }

        $pin = $this->api->sendPin($phone);

        return [
            'step'  => 'pin',
            'phone' => $phone,
            'token' => $pin['session_token'] ?? null,
        ];
    }

    public function confirmPin(string $phone, string $pin, string $token): array
    {
        $res = $this->api->confirmPin($phone, $pin, $token);

        if (($res['status'] ?? null) !== 'SUCCESS') {
            return ['step' => 'error'];
        }

        return ['step' => 'subscribed'];
    }
}

