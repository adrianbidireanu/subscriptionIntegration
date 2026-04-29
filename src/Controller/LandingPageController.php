<?php

namespace App\Controller;

use App\Constants\PhoneSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\SubscriptionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LandingPageController extends AbstractController
{
    private const GENERIC_ERROR = "An error has occurred, sorry for this";

    #[Route('/', name: 'landing_page', methods: ['GET'])]
    #[Route('/enter-phone', name: 'msisdn_entry_page', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('landing/msisdn_entry_page.html.twig', [
            'phone_prefix'      => PhoneSettings::PHONE_PREFIX,
            'phone_regex'       => PhoneSettings::PHONE_REGEX,
            'phone_placeholder' => PhoneSettings::PHONE_PLACEHOLDER,
        ]);
    }

    #[Route('/enter-pin', name: 'pin_page', methods: ['GET'])]
    public function pin(): Response
    {
        return $this->render('landing/pin_entry_page.html.twig', [
            'pin_regex'       => PhoneSettings::PIN_REGEX,
            'pin_placeholder' => PhoneSettings::PIN_PLACEHOLDER,

        ]);
    }

    #[Route('/send-pin', name: 'send-pin', methods: ['POST'])]
    public function sendPin(
        Request $request,
        SubscriptionService $service,
        SessionInterface $session
    ): Response {
        $phone = $this->prepareMsisdn($request->request->get('phone'));

        $result = $service->sendPin($session, $phone);

        $session->set('phone', $result['phone'] ?? $phone);

        if ($result['step'] === 'pin') {
            return $this->redirectToRoute('pin_page');
        }

        return $this->render('landing/error_page.html.twig', [
            'error' => $result['message'] ?? self::GENERIC_ERROR,
        ]);
    }

    #[Route('/confirm-pin', name: 'confirm_pin', methods: ['POST'])]
    public function confirmPin(
        Request $request,
        SubscriptionService $service,
        SessionInterface $session
    ): Response {
        $pin = $request->request->get('pin');

        $result = $service->confirmPin($session, $pin);

        $session->set('step', $result['step']);

        if ($result['step'] === 'subscribed') {
            return $this->render('landing/success_page.html.twig');
        }

        return $this->render('landing/error_page.html.twig', [
            'error' => $result['message'] ?? self::GENERIC_ERROR,
        ]);
    }

    private function prepareMsisdn(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        $phone = substr($phone, PhoneSettings::PHONE_MINIMUM_DIGITS);

        return PhoneSettings::PHONE_PREFIX . $phone;
    }
}
