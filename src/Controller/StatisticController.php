<?php

namespace App\Controller;
use App\Repository\TransportRepository;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Ticket;
use App\Entity\Transport;

use Symfony\Component\Routing\Annotation\Route;

class StatisticController extends AbstractController
{
    #[Route('/statistics', name: 'statistics')]
    public function showStatistics(
        TicketRepository $ticketRepository,
        TransportRepository $transportRepository
    ): Response {
        // Calculate average ticket price
        $averageTicketPrice = $ticketRepository->calculateAveragePrice();

        // Calculate transport type percentages
        $transportPercentages = $transportRepository->getTransportTypePercentages();

        // Retrieve ticket with highest and lowest price
        $ticketWithHighestPrice = $ticketRepository->findTicketWithHighestPrice();
        $ticketWithLowestPrice = $ticketRepository->findTicketWithLowestPrice();

        // Calculate ticket status percentages
        $ticketStatusPercentages = $ticketRepository->calculateTicketStatusPercentage();

        return $this->render('statistics/show.html.twig', [
            'averageTicketPrice' => $averageTicketPrice,
            'transportPercentages' => $transportPercentages,
            'ticketWithHighestPrice' => $ticketWithHighestPrice,
            'ticketWithLowestPrice' => $ticketWithLowestPrice,
            'ticketStatusPercentages' => $ticketStatusPercentages,
        ]);
    }
}
