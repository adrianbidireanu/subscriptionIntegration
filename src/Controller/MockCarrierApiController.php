<?php

namespace App\Controller;

use App\Enum\SubscriptionCode;
use App\Service\ApiResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MockCarrierApiController extends AbstractController
{
    private const TEST_ALREADY_SUBSCRIBED_MSISDN = '+965123456';
    private const TEST_INVALID_PIN               = '1234';

    public function __construct(
        private readonly ApiResponseFactory $responseFactory
    ) {
    }

    #[Route('/mock/check-subscription', name: 'mock_check', methods: ['POST'])]
    public function checkSubscription(Request $request): JsonResponse
    {
        $phone = $request->toArray()['phone'] ?? null;
        $token = $request->toArray()['token'] ?? null;

        $code = $phone === self::TEST_ALREADY_SUBSCRIBED_MSISDN
            ? SubscriptionCode::USER_ALREADY_SUBSCRIBED
            : SubscriptionCode::USER_NOT_SUBSCRIBED;

        if (!empty($token)) {
            $code = SubscriptionCode::SUBSCRIBED;
        }

        return $this->json(
            $this->responseFactory->create($code, ['token' => $this->generateToken()])
        );
    }

    #[Route('/mock/send-pin', name: 'mock_send_pin', methods: ['POST'])]
    public function sendPin(Request $request): JsonResponse
    {
        return $this->json(
            $this->responseFactory->create(
                SubscriptionCode::PIN_SENT,
            )
        );
    }

    #[Route('/mock/confirm-pin', name: 'mock_confirm_pin', methods: ['POST'])]
    public function confirmPin(Request $request): JsonResponse
    {
        $data = $request->toArray();

        if (($data['pin'] ?? null) === self::TEST_INVALID_PIN) {
            return $this->json(
                $this->responseFactory->create(
                    SubscriptionCode::INVALID_PIN
                ),
            );
        }

        return $this->json(
            $this->responseFactory->create(
                SubscriptionCode::SUBSCRIBED
            )
        );
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}
