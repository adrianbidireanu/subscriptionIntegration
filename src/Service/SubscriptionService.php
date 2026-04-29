<?php

namespace App\Service;

use App\Enum\SubscriptionCode;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SubscriptionService
{
    public function __construct(
        private readonly CarrierApiClient $api
    ) {
    }

    public function sendPin(SessionInterface $session, string $phone): array
    {
        $userStatus = $this->api->checkSubscription($phone);

        if (($userStatus['code'] ?? null) === SubscriptionCode::USER_NOT_SUBSCRIBED->value) {
            $session->set('token', $userStatus['token'] ?? null);
        } else {
            return [
                'step'    => 'error',
                'message' => $userStatus['message'],
            ];
        }

        $sendPinResponse = $this->api->sendPin($phone, $userStatus['token']);

        if (($sendPinResponse['code'] ?? null) === SubscriptionCode::PIN_SENT->value) {
            return [
                'step' => 'pin',
            ];
        } else {
            return [
                'step'    => 'error',
                'message' => $sendPinResponse['message'],
            ];
        }
    }

    public function confirmPin(SessionInterface $session, string $pin): array
    {
        $phone = $session->get('phone');
        $token = $session->get('token');

        $confirmPinResponse = $this->api->confirmPin($phone, $pin, $token);

        if (($confirmPinResponse['code'] ?? null) !== SubscriptionCode::SUBSCRIBED->value) {
            return [
                'step'    => 'error',
                'message' => $confirmPinResponse['message'],
            ];
        }

        $userStatus = $this->api->checkSubscription($phone, $session->get('token'));

        if (($userStatus['code'] ?? null) !== SubscriptionCode::SUBSCRIBED->value) {
            return [
                'step'    => 'error',
                'message' => $userStatus['message'],
            ];
        }

        return [
            'step' => 'subscribed',
        ];
    }
}

