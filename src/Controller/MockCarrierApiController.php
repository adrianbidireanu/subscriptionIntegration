<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MockCarrierApiController extends AbstractController
{
    #[Route('/mock/check-subscription', name: 'mock_check', methods: ['POST'])]
    public function checkSubscription(Request $request): JsonResponse
    {
        $phone = $request->toArray()['phone'] ?? null;

        if ($phone === '000') {
            return $this->json([
                'status' => 'ALREADY_SUBSCRIBED',
            ]);
        }

        return $this->json([
            'status' => 'NOT_SUBSCRIBED',
        ]);
    }

    #[Route('/mock/send-pin', name: 'mock_send_pin', methods: ['POST'])]
    public function sendPin(Request $request): JsonResponse
    {
        return $this->json([
            'session_token' => 'mock-token-123',
            'status'        => 'PIN_SENT',
        ]);
    }

    #[Route('/mock/confirm-pin', name: 'mock_confirm_pin', methods: ['POST'])]
    public function confirmPin(Request $request): JsonResponse
    {
        $data = $request->toArray();

        if (($data['pin'] ?? null) !== '1234') {
            return $this->json([
                'status' => 'ERROR',
            ], 400);
        }

        return $this->json([
            'status' => 'SUCCESS',
        ]);
    }
}
