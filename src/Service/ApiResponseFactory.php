<?php

namespace App\Service;

use App\Enum\SubscriptionCode;

class ApiResponseFactory
{
    public function create(SubscriptionCode $code, array $data = []): array
    {
        return array_merge($data, [
            'status'  => $code->status(),
            'code'    => $code->value,
            'message' => $code->message(),
        ]);
    }
}
