<?php

namespace App\Enum;

use App\Constants\SubscriptionStatus;

enum SubscriptionCode: string
{
    case PIN_SENT = 'pin_sent';
    case SUBSCRIBED = 'subscribed';
    case USER_ALREADY_SUBSCRIBED = 'user_already_subscribed';
    case USER_NOT_SUBSCRIBED = 'user_not_subscribed';
    case INVALID_PIN = 'invalid_pin';

    public function message(): string
    {
        return match ($this) {
            self::PIN_SENT                => 'PIN has been sent to the user',
            self::SUBSCRIBED              => 'User subscribed successfully',
            self::USER_ALREADY_SUBSCRIBED => 'User is already subscribed',
            self::USER_NOT_SUBSCRIBED     => 'User is not already subscribed',
            self::INVALID_PIN             => 'Invalid PIN provided',
        };
    }

    public function status(): string
    {
        return match ($this) {
            self::SUBSCRIBED, self::PIN_SENT, self::USER_NOT_SUBSCRIBED => SubscriptionStatus::SUCCESS,
            default                                                     => SubscriptionStatus::ERROR,
        };
    }
}
