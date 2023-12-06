<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ParticipationRepository;
use Doctrine\Persistence\ManagerRegistry;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Participation;
use App\Entity\User2;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use BaconQrCode\Encoder\QrCode as BaconQrCode;
use Endroid\QrCode\QrCode as EndroidQrCode;
use App\Repository\EventRepository;
use App\Entity\Event;
use Endroid\QrCode\QrCode;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\PayPalService;
use Omnipay\Omnipay;
use Omnipay\Common\GatewayInterface;
use DateTime;
use App\Service\QrCodeService;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\RequestStack;








class ParticipationController extends AbstractController
{


    private $requestStack;
    protected $builder;
    protected $qrcodesDirectory;
    public function __construct( RequestStack $requestStack, BuilderInterface $builder)
    {
     //   $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
        $this->builder = $builder;
        // $this->qrcodesDirectory = 'public/qrcodes';
    }



   

    


    #[Route('/participation', name: 'app_participation')]
    public function index(): Response
    {
        return $this->render('participation/index.html.twig', [
            'controller_name' => 'ParticipationController',
        ]);
    }

    #[Route('/afficheparticipation', name: 'afficheparticipation')]
    public function afficheparticipation(ParticipationRepository $repo): Response
    {
        $participation = $repo->findAll();
        return $this->render('participation/afficheparticipation.html.twig', [
            'participation' => $participation,
        ]);
    }


    #[Route('/supprimeparticipation/{i}', name: 'supprimeparticipation')]
    public function Supprimeparticipation($i,ParticipationRepository $repo,ManagerRegistry  $doctrine): Response
    {

         //recuperer l auteur a supprimer
         $participation=$repo->find($i);
          //recuperer le entity manager;le chef d orchestre de Orm
         $em=$doctrine->getManager();
         //action suppression
         $em->remove($participation);
         //commit
         $em->flush();
        return $this->redirectToRoute('afficheparticipation');
    }
    
    #[Route('/paiement/{i}', name: 'paiement')]
    public function paiement($i): Response
    {
        $session = $this->requestStack->getSession();
            $user = $session->get('User2') ;
        // Récupérer l'événement
        $event = $this->getDoctrine()->getRepository(Event::class)->find($i);
    
        // Vérifier si l'événement existe
        if (!$event) {
            // Rediriger ou afficher un message d'erreur, selon vos besoins
            // ...
    
            // Exemple de redirection vers la page d'accueil
            return $this->redirectToRoute('affichecard');
        }
    
        // Vérifier si le nombre maximal de participants n'a pas été atteint
        if ($event->getNbParticipants() < $event->getNbmaxparticipant()) {
            // Récupérer l'utilisateur avec l'ID 1
            
            $user = $this->getDoctrine()->getRepository(User2::class)->find($user->getIduser());
    
            // Vérifier si l'utilisateur existe
            if (!$user) {
                // Rediriger ou afficher un message d'erreur, selon vos besoins
                // ...
    
                // Exemple de redirection vers la page d'accueil
                return $this->redirectToRoute('affichecard');
            }
    
            // Récupérer le coût de participation à partir de l'événement
            $participationCost = $event->getPrixTicket();
    
            // Vérifier le solde du portefeuille de l'utilisateur
            $walletBalance = $user->getWalletuser();
           

            $generatedQrCode = $this->generateEventParticipantQrCode($user->getNomuser(), $event->getNomevent());
           

            // Vérifier si l'utilisateur a suffisamment d'argent
            if ($walletBalance >= $participationCost) {
                // Déduire le coût de participation du portefeuille de l'utilisateur
                $user->setWalletuser($walletBalance - $participationCost);
    
                // Enregistrez la participation
                $participation = new Participation();
                $participation->setUser2($user);
                $participation->setEvent($event);
                $participation->setPaymentStatus(true);
                $participation->setCodeQR($generatedQrCode);

               // $participation->setCodeQR('/qrcodes/' . $namePng);

                $participation->setDateParticipation(new DateTime());
    
                // Incrémenter le nombre actuel de participants de l'événement
                $event->setNbParticipants($event->getNbParticipants() + 1);
    
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($participation);
                $entityManager->flush();
    
                // Rediriger ou afficher un message de réussite
                // ...
    
                return $this->redirectToRoute('participation_reussi',[
                    'participationId' => $participation->getId(),
                ]);
            
            } else {
                // Rediriger ou afficher un message indiquant que l'utilisateur n'a pas assez d'argent
                // ...
    
                return $this->redirectToRoute('insufficient_funds_page');
            }
        } else {
            // Rediriger ou afficher un message indiquant que l'événement est complet
            // ...
    
            return $this->redirectToRoute('evenement_complet_page');
        }
    }
    
    




    #[Route('/evenement_complet_page', name: 'evenement_complet_page')]
    public function evenement_complet_page(Request $request): Response
    {
        // Vous pouvez récupérer des données de la requête si nécessaire
        

        // Ajoutez d'autres logiques si nécessaire, puis renvoyez la réponse appropriée
        return $this->render('participation/evenement_complet_page.html.twig', [
            
        ]);
    }
 



    #[Route('/participation_reussi', name: 'participation_reussi')]
public function participationReussi(Request $request, ParticipationRepository $participationRepository): Response
{
    // Récupérez l'ID de la participation depuis la requête
    $participationId = $request->query->get('participationId');

    // Récupérez la participation depuis le repository
    $participation = $participationRepository->find($participationId);

    // Ajoutez d'autres logiques si nécessaire, puis renvoyez la réponse appropriée
    return $this->render('participation/participation_reussi.html.twig', [
        'participation' => $participation,
    ]);
}


    


    #[Route('/insufficient_funds_page', name: 'insufficient_funds_page')]
     public function participation_echec(Request $request): Response
{
    // Vous pouvez récupérer des données de la requête si nécessaire
   

    // Ajoutez d'autres logiques si nécessaire, puis renvoyez la réponse appropriée
    return $this->render('participation/insufficient_funds_page.html.twig', [
        
    ]);
}

#[Route('/erreur', name: 'erreur')]
    public function erreur(Request $request): Response
    {
        $error_message = $request->query->get('error_message');
    
        return $this->render('participation/erreur.html.twig', [
            'error_message' => $error_message,
        ]);
    }
    




    #[Route('/Depassnbmax', name: 'Depassnbmax')]
    public function Depassnbmax(): Response
    {
        $paypalClientId = $this->getParameter('paypal_client_id');
        // Cette méthode gère la page affichée lorsque le nombre maximal de participants est atteint
        return $this->render('event/Depassnbmax.html.twig',[
            
            'paypal_client_id' => $paypalClientId,
    ]);
    }


    

    //CORRRIGER NB PARTICIATION ACTUELLE
    //ROUTE DE PAY SUCCESSFUL


    public function generateEventParticipantQrCode($username, $eventId)
    {
        // Customize the QR code data as per your requirements
        $qrCodeData = $username . '_' . $eventId;
    
        $result = $this->builder
            ->data($qrCodeData)
            ->size(400)
            ->encoding(new Encoding('UTF-8'))
            ->build();
    
        // Generate a unique PNG file name
        $namePng = uniqid('qr_code_') . '.png';
        $qrcodesDirectory = $this->getParameter('kernel.project_dir') . '/public/qrcodes';

    
        // Save the QR code image to the public/qrcodes directory
        $result->saveToFile($qrcodesDirectory . '/' . $namePng);
    
        // Return the data URI of the generated QR code or the file path
        return '/qrcodes/' . $namePng;
    }







    #[Route('/userStatistics', name: 'userStatistics')]
    public function userStatistics(ParticipationRepository $participationRepository)
    {
        $userParticipationCounts = $participationRepository->countParticipationsByUser();
    
        return $this->render('participation\user_statistics.html.twig', [
            'userParticipationCounts' => $userParticipationCounts,
        ]);
    }




}