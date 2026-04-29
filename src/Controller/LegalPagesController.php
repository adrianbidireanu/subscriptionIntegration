<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class LegalPagesController extends AbstractController
{
    #[Route('/terms', name: 'terms_page', methods: ['GET'])]
    public function terms(): Response
    {
        return $this->render('legal/terms.html.twig');
    }

    #[Route('/contact', name: 'contact_page', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('legal/contact.html.twig');
    }
}
