<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CarrierApiClient
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $carrierBaseUrl
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function checkSubscription(string $phone): array
    {

        return $this->client->request('POST', $this->carrierBaseUrl . '/check-subscription', [
            'json' => ['phone' => $phone],
        ])->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function sendPin(string $phone): array
    {

        return $this->client->request('POST', $this->carrierBaseUrl . '/send-pin', [
            'json' => ['phone' => $phone],
        ])->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function confirmPin(string $phone, string $pin, string $token): array
    {
        return $this->client->request('POST', $this->carrierBaseUrl . '/confirm-pin', [
            'json' => [
                'phone'         => $phone,
                'pin'           => $pin,
                'session_token' => $token,
            ],
        ])->toArray();
    }
}
