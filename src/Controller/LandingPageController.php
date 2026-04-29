<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\SubscriptionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LandingPageController extends AbstractController
{
    #[Route('/', name: 'landing_page', methods: ['GET'])]
    #[Route('/enter-phone', name: 'msisdn_entry_page', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('landing/msisdn_entry_page.html.twig');
    }

    #[Route('/enter-pin', name: 'pin_page', methods: ['GET'])]
    public function pin(): Response
    {
        return $this->render('landing/pin_entry_page.html.twig');
    }

    #[Route('/send-pin', name: 'send-pin', methods: ['POST'])]
    public function sendPin(
        Request $request,
        SubscriptionService $service,
        SessionInterface $session
    ): Response {
        $phone = $request->request->get('phone');
        $session->start();
        $session->save();

        $result = $service->sendPin($phone);

        $session->set('step', $result['step']);
        $session->set('phone', $result['phone'] ?? $phone);
        $session->set('token', $result['token'] ?? null);

        if ($result['step'] === 'pin') {
            return $this->redirectToRoute('pin_page');
        }

        if ($result['step'] === 'subscribed') {
            return $this->render('landing/success_page.html.twig');
        }

        return $this->render('landing/error_page.html.twig');
    }

    #[Route('/confirm-pin', name: 'confirm_pin', methods: ['POST'])]
    public function confirmPin(
        Request $request,
        SubscriptionService $service,
        SessionInterface $session
    ): Response {
        $pin   = $request->request->get('pin');
        $phone = $session->get('phone');
        $token = $session->get('token');

        $result = $service->confirmPin($phone, $pin, $token);

        $session->set('step', $result['step']);

        if ($result['step'] === 'subscribed') {
            return $this->render('landing/success_page.html.twig');
        }

        return $this->render('landing/pin.html.twig', [
            'error' => 'Invalid PIN',
        ]);
    }
}
