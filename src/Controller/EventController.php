<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EventType;
use App\Form\TimeType;
use App\Form\SearchByNameType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Participation;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User2;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Service\MailService;
use App\Repository\ParticipationRepository;
use App\Repository\User2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Twilio\Rest\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\RequestStack;








class EventController extends AbstractController
{






    private $requestStack;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RequestStack $requestStack)
    {
     //   $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }





    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/affichevent', name: 'affichevent')]
    public function affiche(EventRepository $repo): Response
    {
        $events = $repo->findAll();
      //  $paypalClientId = $this->getParameter('paypal_client_id');
        return $this->render('event/afficheevent.html.twig', [
            'events' => $events,
          //  'paypal_client_id' => $paypalClientId,
        ]);
    }
    


   

    #[Route('/affichecard', name: 'affichecard')]
    public function affichecard(EventRepository $repo): Response
    {
        // Récupérer uniquement les événements valides
        $validEvents = $repo->findBy(['valid' => true]);
        
    //    $paypalClientId = $this->getParameter('paypal_client_id');

        return $this->render('event/affichecard.html.twig', [
            'events' => $validEvents,
           // 'paypal_client_id' => $paypalClientId,
            
        ]);
    }
    





    #[Route('/supprimevent/{i}', name: 'supprimevent')]
    public function DeleteEvent($i,EventRepository $repo,ManagerRegistry  $doctrine): Response
    {

         //recuperer l auteur a supprimer

         $event=$repo->find($i);
          //recuperer le entity manager;le chef d orchestre de Orm
         $em=$doctrine->getManager();
         $participations = $em->getRepository(Participation::class)->findBy(['event' => $event]);

    // Supprimer les participations liées à cet événement
    foreach ($participations as $participation) {
        $em->remove($participation);
    }
         //action suppression
         $em->remove($event);
         //commit
         $em->flush();
        return $this->redirectToRoute('affichevent');
    }
    
   

#[Route('/editevent/{i}', name: 'editevent')]
    public function editevent($i,ManagerRegistry $doctrine , EventRepository $repo,Request $req): Response
    {

        $event=$repo->find($i);
        // Créez un formulaire pour l'édition de l'étudiant en utilisant StudentformType
        $form = $this->createForm(EventType::class, $event);

        // Traitez la soumission du formulaire
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vous n'avez pas besoin d'appeler persist() car $student est déjà géré par Doctrine
            $em = $doctrine->getManager();
            $em->flush();

            // Redirigez l'utilisateur vers la page d'affichage des étudiants
            return $this->redirectToRoute('affichevent');
        }

        return $this->render('event/editevent.html.twig', [
            'form' => $form->createView(),
            'event' => $event, // Vous pouvez passer l'étudiant pour afficher des informations existantes dans le formulaire
        ]);
    }


    #[Route('/searchbyname', name: 'searchbyname')]
public function searchByName(EventRepository $eventRepository, Request $request): Response
{
    $query = $request->query->get('query');
    $events = null;

    // Check if a search query is provided
    if ($query) {
        // If a query is provided, perform the search
        $events = $eventRepository->searchByName($query);
    } else {
        // If no query is provided, fetch all events
        $events = $eventRepository->findAll();
    }

    return $this->render('event/affichecard.html.twig', [
        'events' => $events,
        'query' => $query,
    ]);
}

    



    #[Route('/addEvent', name: 'addEvent')]
    public function ajoutA(ManagerRegistry $doctrine, Request $req): Response
    { 
        // Instancier l'objet Event
        $event = new Event();
    
        // Créer le formulaire
        $form = $this->createForm(EventType::class, $event);
    
        // Récupérer les données saisies dans le formulaire
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            $imageFile = $form->get('image')->getData();
    
            if ($imageFile) {
                // Générer un nom unique pour le fichier
                $newFilename = uniqid().'.'.$imageFile->getClientOriginalExtension();
    
                // Déplacer le fichier dans le répertoire où les images sont stockées
                $imageFile->move(
                    $this->getParameter('eventim_directory'),
                    $newFilename
                );
    
                // Stocker le nom du fichier dans la base de données
                $event->setImage($newFilename);
            }
    
            // Définir la date de création sur la date actuelle
            $event->setDateCreation(new \DateTime());
    
            // Enregistrer l'événement dans la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
    
            return $this->redirectToRoute('affichevent');
        }
    
        return $this->render('event/addEvent.html.twig', [
            'form' => $form->createView(),
        ]);
    }





    



  


 
    #[Route('/showDetails/{i}', name: 'showDetails')]
public function showDetails($i, EventRepository $repo)
{
    // Récupérer l'événement depuis le référentiel
    $event = $repo->find($i);

    // Vérifier si l'événement existe
    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }

    // Récupérer le client ID PayPal
    //$paypalClientId = $this->getParameter('paypal_client_id');

    // Passer les données nécessaires à votre vue
    return $this->render('event/showdetails.html.twig', [
        'event' => $event,
        //'paypal_client_id' => $paypalClientId,
    ]);
}




#[Route('/panier/{i}', name: 'panier')]
public function ajouterAuPanier(int $i): Response
{
    $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;
        
    $entityManager = $this->getDoctrine()->getManager();
    $eventRepository = $entityManager->getRepository(Event::class);
    $userRepository = $entityManager->getRepository(User2::class);

    $event = $eventRepository->find($i);

    if (!$event) {
        $this->addFlash('danger', 'L\'événement n\'a pas été trouvé.');
        return $this->redirectToRoute('affichecard');  // Redirigez vers une page appropriée
    }

    // Remplacez 1 par l'id de l'utilisateur que vous souhaitez
    $user = $userRepository->find($user->getIduser());

    if ($user) {
        $user->addPanier($event);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'L\'événement a été ajouté au panier de l\'utilisateur avec succès.');
        return $this->redirectToRoute('page_panier_succes', ['i' => $event->getIdEvent()]);
    } else {
        $this->addFlash('danger', 'L\'utilisateur n\'a pas été trouvé.');
        return $this->redirectToRoute('affichecard');  // Redirigez vers une page appropriée
    }
}


#[Route('/page_panier_succes', name: 'page_panier_succes')]
public function pagePanierSucces(): Response
{
    return $this->render('event/succes.html.twig');
}



    
   
#[Route('/Monpanier', name: 'Monpanier')]
public function Monpanier(): Response
{
    // Remplacez 1 par l'ID de l'utilisateur que vous souhaitez
   // $userId = 1;
   $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;

    // Récupérer l'utilisateur par son ID
    $user = $this->getDoctrine()->getRepository(User2::class)->find($user->getIduser());

    // Vérifier si l'utilisateur existe
    if (!$user) {
        // Rediriger ou afficher un message d'erreur, selon vos besoins
        // ...

        // Exemple de redirection vers la page d'accueil
        return $this->redirectToRoute('affichecard');
    }

    // Récupérer les événements dans le panier de l'utilisateur
    $eventsInPanier = $user->getPanier();

    // Vérifier si le panier est vide
    if (empty($eventsInPanier)) {
        // Rediriger vers la page videpanier avec un message
        return $this->redirectToRoute('vide');
    }

    // Passer les événements à la vue
    return $this->render('event/monpanier.html.twig', [
        'eventsInPanier' => $eventsInPanier,
    ]);
}

#[Route('/vide', name: 'vide')]
public function vide(): Response
{
    return $this->render('event/vide.html.twig', [
        'msg' => 'Votre panier est vide!',
    ]);
}



#[Route('/cancelEvent/{i}', name: 'cancelEvent')]
public function cancelEvent($i, EventRepository $eventRepository, ParticipationRepository $partrepo, EventDispatcherInterface $eventDispatcher): Response
{
    // Récupérer l'événement depuis le repository
    $event = $eventRepository->find($i);

    if (!$event) {
        throw $this->createNotFoundException('Événement non trouvé');
    }

    $adminPhoneNumber = '+21699607317'; // Numéro de téléphone de l'administrateur

    // Vérifier si l'événement n'est pas déjà annulé
    if ($event->getValid()) {
        // Mettre à jour le champ valid à false
        $event->setValid(false);
        $partrepo->updatePaymentStatusByEventId($i);

        // Enregistrez les modifications dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
          
        $adminPhoneNumber=+21699607317;
        // Envoyer un SMS à l'administrateur
        $message = sprintf('L\'événement "%s" prévu pour le %s a été annulé.', $event->getNomevent(), $event->getDatedebutevent()->format('Y-m-d'));
        $this->sendSms($adminPhoneNumber, $message);

        // Ajouter un message flash pour informer l'administrateur de l'annulation (facultatif)
        $this->addFlash('success', 'L\'événement a été annulé avec succès.');

        // Rediriger vers une page de succès
        return $this->redirectToRoute('affichecancel');
    } else {
        // Ajouter un message flash pour informer l'utilisateur que l'événement est déjà annulé (facultatif)
        $this->addFlash('info', 'L\'événement est déjà annulé.');

        // Rediriger vers une autre page si nécessaire
        return $this->redirectToRoute('affichecancel');
    }
}




#[Route('/affichecancel', name: 'affichecancel')]
    public function affichecancel(): Response
    {
        return $this->render('event/cancel.html.twig', 
            
        );
    }




private function sendSms($phoneNumber, $message)
{
    // Remplacez ces valeurs par vos propres clés Twilio
    $accountSid = 'ACa17a97ab87a2159a860330be83fb50dc';
    $authToken = '6fbbec5c0a2aa04f8f1cca2ab1e37680';
    $twilioPhoneNumber = '+18325322932';

    // Initialisez le client Twilio
    $twilio = new Client($accountSid, $authToken);

    try {
        // Envoyez le SMS
        $twilio->messages->create(
            $phoneNumber, // Numéro de téléphone du destinataire
            [
                'from' => $twilioPhoneNumber, // Utilisez le numéro Twilio comme émetteur
                'body' => $message,
            ]
        );
    } catch (\Exception $e) {
        // Gérez les erreurs d'envoi de SMS ici
        // Vous pouvez journaliser l'erreur ou prendre d'autres mesures nécessaires
        dump($e->getMessage()); // À des fins de débogage
    }
}


#[Route('/stat', name: 'stat')]
public function stat(): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $sportTotalParticipants = $entityManager->getRepository(Event::class)->countTotalParticipantsByEventType('Sport');
    $loisirTotalParticipants = $entityManager->getRepository(Event::class)->countTotalParticipantsByEventType('Loisir');
    $cultureTotalParticipants = $entityManager->getRepository(Event::class)->countTotalParticipantsByEventType('Culture');

    return $this->render('event/stat.html.twig', [
        'sportTotalParticipants' => $sportTotalParticipants,
        'loisirTotalParticipants' => $loisirTotalParticipants,
        'cultureTotalParticipants' => $cultureTotalParticipants,
    ]);
}

#[Route('/eventsbloc', name: 'eventsbloc')]
    public function eventsblock(Request $request, $status = 'all'): Response
    {
        // Récupérer l'EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Récupérer les événements en fonction du statut
        if ($status == 'valid') {
            $events = $entityManager->getRepository(Event::class)->findBy(['valid' => true]);
        } elseif ($status == 'nonvalid') {
            $events = $entityManager->getRepository(Event::class)->findBy(['valid' => false]);
        } else {
            // Statut "all" ou tout autre statut non reconnu, afficher tous les événements
            $events = $entityManager->getRepository(Event::class)->findAll();
        }

        return $this->render('event/bloc.html.twig', [
            'events' => $events,
            'status' => $status,
        ]);
    }




   
  

    #[Route('/stats2', name: 'stats2')]
    public function categoryPieChart(EventRepository $eventRepository): Response
    {
        $categories = $eventRepository->countEventsByCategory();
    
        $data = [
            'labels' => array_keys($categories),
            'datasets' => [
                [
                    'data' => array_values($categories),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        // ... add more colors as needed
                    ],
                ],
            ],
        ];
    
        return $this->render('event/stat2.html.twig', [
            'categoryData' => json_encode($data),
        ]);
    }
    















}