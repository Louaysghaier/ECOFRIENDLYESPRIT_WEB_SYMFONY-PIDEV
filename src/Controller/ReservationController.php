<?php

namespace App\Controller;

use App\Form\ReservationType;
use App\Repository\TicketRepository;
use Stripe\Stripe;
use Stripe\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function reserveTicket(Request $request, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(ReservationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Accédez directement aux propriétés du formulaire
            $lieuDepart = $form->get('lieuDepart')->getData();
            $lieuArrive = $form->get('lieuArrive')->getData();
            $dateTicket = $form->get('dateTicket')->getData();

            // Appel de la méthode findAvailableTickets du TicketRepository
            $availableTickets = $ticketRepository->findAvailableTickets([
                'lieuDepart' => $lieuDepart,
                'lieuArrive' => $lieuArrive,
                'dateTicket' => $dateTicket,
            ]);
            return $this->render('reservation/results.html.twig', [
                'tickets' => $availableTickets,
                'reservationData' => [
                    'lieuDepart' => $lieuDepart,
                    'lieuArrive' => $lieuArrive,
                    'dateTicket' => $dateTicket,
                ],
            ]);
        }

        // Afficher le formulaire de réservation
        return $this->render('reservation/reserve.html.twig', [
            'form' => $form->createView(),
        ]);
    }
   
    


    



   
}