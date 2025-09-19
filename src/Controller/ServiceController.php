<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ServiceController extends AbstractController
{
    #[Route('/showservice/{name}', name: 'app_service_show')]
    public function showService(string $name): Response
    {
        return $this->render('service/showService.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/showservice/go/to/index', name: 'app_service_goto_index')]
    public function goToIndex(): Response
    {
        // Redirection vers la méthode index du HomeController
        return $this->redirectToRoute('app_home');
    }
}
