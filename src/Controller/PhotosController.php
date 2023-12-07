<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotosController extends AbstractController
{
   /**
     * @Route("/photos-gallery", name="app_photos_gallery")
     */
    public function gallery(): Response
    {
        // Example data - replace with your actual data
        $vehicles = [
            ['title' => 'Train', 'image' => 'train.png', 'rating' => 4],
            ['title' => 'Métro', 'image' => 'metro.png', 'rating' => 3],
            ['title' => 'Bus', 'image' => 'bus.png', 'rating' => 5],
            ['title' => 'Voiture', 'image' => 'voiture.png', 'rating' => 2],
            ['title' => 'Taxi', 'image' => 'taxi.png', 'rating' => 4],
        ];

        // Render the template with the data
        return $this->render('reservation/photos.html.twig', ['vehicles' => $vehicles]);
    }
}
