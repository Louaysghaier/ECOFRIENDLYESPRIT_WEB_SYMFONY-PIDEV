<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface; // Import FlashBagInterface

use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{ private $requestStack;
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag,RequestStack $requestStack)
    {
        $this->flashBag = $flashBag;
        $this->requestStack = $requestStack;
    }
    #[Route('/payment/payer/{ticketId}', name: 'app_payment_payer')]
    public function payForTicket(Request $request, int $ticketId, TicketRepository $ticketRepository): Response
    {

        $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        // Retrieve the ticket from the database
        $ticket = $ticketRepository->find($ticketId);

        if (!$ticket) {
            throw $this->createNotFoundException('Ticket not found');
        }
       // $userRepository = $entityManager->getRepository(User2::class);
        // Retrieve the associated user
        //$user = $userRepository->find($user->getIduser());

        //$user = $ticket->getIduser();


        // Access the walletUser attribute of the User class
        $walletUser = $user->getWalletUser();

        // Retrieve the ticket price
        $ticketPrice = $ticket->getPrix();
        $username=$user->getNomuser();

        // Check if the wallet balance is sufficient
        if ($walletUser >= $ticketPrice) {
            // Perform payment logic by deducting the ticket price from the wallet balance
            $walletUser -= $ticketPrice;

            // Update the wallet balance
            $user->setWalletUser($walletUser);

            // Update the ticket status to "Réservé"
            $ticket->setStatutTicket('Réservé');

            // Save the changes
            $this->getDoctrine()->getManager()->flush();

            $reservationData = $request->query->get('reservationData');
            
            // Add a success flash message
            $this->flashBag->add('success', 'Le paiement a été effectué avec succès. Vous pouvez télécharger le ticket en PDF par un simple clic sur le ticket.');
            return $this->render('payment/success.html.twig', [
                'ticket' => $ticket,
                'user' => $user,
                'walletUser' => $walletUser,
                'ticketPrice' => $ticketPrice,
                'reservationData' => $reservationData,
                'username'=>$username,
            ]);
        } else {
            return $this->render('payment/error.html.twig', [
                'ticket' => $ticket,
                'user' => $user,
                'walletUser' => $walletUser,
                'ticketPrice' => $ticketPrice,
                'username'=>$username,
            ]);
        }
    }

    #[Route('/payment/pdf/{idBillet}', name: 'payment_pdf')]
    public function showPdfDetails(int $idBillet, TicketRepository $ticketRepository): Response
    {
        $billet = $ticketRepository->find($idBillet);

        if (!$billet) {
            throw $this->createNotFoundException('Billet non trouvé');
        }

        $html = $this->renderView('payment/pdf.html.twig', ['ticket' => $billet]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Générez un nom de fichier unique pour le PDF
        $filename = 'billet_' . $idBillet . '.pdf';

        // Renvoie la réponse avec le contenu PDF en tant que téléchargement
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}
