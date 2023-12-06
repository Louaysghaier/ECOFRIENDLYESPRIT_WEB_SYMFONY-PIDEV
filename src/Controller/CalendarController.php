<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;

class CalendarController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }






    #[Route('/calendar', name: 'calendar')]
    public function calendar(EventRepository $eventRepository): Response
    {
        // Assuming your Event entity has properties like id, start, etc.
        $events = $eventRepository->findAll();

        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getIdevent(),
                'start' => $event->getDatedebutevent()->format('Y-m-d H:i:s'),
                'name' => $event->getNomevent(),
                // Adjust other properties based on your Event entity
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('calendar/calendar.html.twig', compact('data'));
    }





}
