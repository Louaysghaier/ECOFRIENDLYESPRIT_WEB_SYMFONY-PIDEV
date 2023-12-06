<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapsController extends AbstractController
{
    #[Route('/maps', name: 'app_maps')]
    public function index(): Response
    {
        return $this->render('maps/show.html.twig', [
            'controller_name' => 'MapsController',
        ]);
    }

    #[Route('/directions', name: 'directions')]
    public function showDirections(): Response
    {
        // Coordonnées de votre université
        $universityCoordinates = [
            'lat' => 36.853685300118,
            'lng' => 10.207445443108,
        ];

        return $this->render('maps/directions.html.twig', [
            'universityCoordinates' => $universityCoordinates,
        ]);
    }


}
