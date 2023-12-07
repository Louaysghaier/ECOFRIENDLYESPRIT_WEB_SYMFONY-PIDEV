<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Documents;
use App\Entity\User2;
use App\Entity\Topic;
use App\Form\DocumentsType;
use App\Form\modifierdocuments;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\DocumentsRepository;
use App\Repository\User2Repository;
use App\Repository\RatingRepository;
use Twig\Environment;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Dompdf\Options;
use App\Entity\Raiting;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use App\Service\FileUploader;
use App\Service\QrCodeService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface; 
use App\Message\DocumentAddedMessage;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Symfony\Component\Messenger\MessageBusInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Twilio\Rest\Client;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\Chartjs\Model\ChartDataset;








class DocumentsController extends AbstractController
{
    private $requestStack;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RequestStack $requestStack)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }

  
    #[Route('/documents', name: 'display_blog')]
    public function index(): Response
    {
       
        $documents = $this->getDoctrine()->getManager()->getRepository(Documents::class)->findAll();
        return $this->render('documents/index.html.twig', [
           'd'=>$documents
        ]);
    }
    
    #[Route('/mylibrary', name: 'mylibrary')]
    public function myLibrary(SessionInterface $session, User2Repository $userRepository): Response
    {
        // Get the user ID from the session
        $iduser = $session->get('User2');
    
        // Fetch user documents based on the user ID
        $userDocuments = $this->getDoctrine()->getRepository(Documents::class)->findBy(['iduser' => $iduser]);
    
        // Pass the documents to your view
        return $this->render('documents/afficherDocumentsUtilisateur.html.twig', ['userDocuments' => $userDocuments]);
    }
    
      
    
    

 
    #[Route('/adddocuments', name: 'app_adddocuments')]
public function adddocuments(Request $request, SluggerInterface $slugger,  EntityManagerInterface $entityManager,MessageBusInterface $messageBus, FlashBagInterface $flashBag,SessionInterface $session,User2Repository $userRepository): Response
{
    
    $documents = new Documents();
    $session = $this->requestStack->getSession();
    $userId = $session->get('User2'); // Assurez-vous que 'userId' est la clé correcte

    // Récupérer l'utilisateur depuis la base de données
    $user = $this->getDoctrine()->getRepository(User2::class)->find($userId);
    $form = $this->createForm(DocumentsType::class, $documents);
    $form->handleRequest($request);
   
    if ($form->isSubmitted() && $form->isValid()) {
      
        // Handle PDF file
        /** @var UploadedFile $brochureFile */
        $brochureFile = $form->get('brochure')->getData();

        if ($brochureFile) {
            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            try {
                $brochureFile->move(
                    $this->getParameter('brochures_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
            }

            $documents->setBrochureFilename($newFilename);
        }

        // Handle image file
        $imageFile = $form->get('document')->getData();

        if ($imageFile) {
            $newImageFilename = uniqid().'.'.$imageFile->guessExtension();

            $imageFile->move(
                $this->getParameter('images_directory'),
                $newImageFilename
            );

            $documents->setDocument($newImageFilename);

        }
        $documents->setIdUser($user);
   
        $entityManager->persist($documents);
        $entityManager->flush();
     
        $documentId = $documents->getId();
      
      $this->sendSms();
       
        return $this->redirectToRoute('AfficheDoc');
    }
    $session->set('sms envoyée avec success', true);
    return $this->render('documents/createDocuments.html.twig', ['f' => $form->createView()]);
}
            #[Route('/pdf/{filename}', name: 'pdf_view', requirements: ['filename' => '.+'])]
            public function viewPdf($filename)
            {
                $brochuresDirectory = $this->getParameter('brochures_directory');
                $filePath = $brochuresDirectory.'/'.$filename;

                $response = new Response();
                $response->headers->set('Content-Type', 'application/pdf');
                $response->headers->set('Content-Disposition', 'inline; filename="'.$filename.'"');
                $response->setContent(file_get_contents($filePath));

                return $response;
            }
            #[Route("/removedoc/{id}", name: 'removedoc', methods: ['GET'])]
            public function suppressiondocuments(Documents $documents): Response
            {
                $em = $this->getDoctrine()->getManager();
            
          
            
                // Ensuite, supprimer le document
                $em->remove($documents);
                $em->flush();
                
                return $this->redirectToRoute('AfficheDocback');
            
            
    }
    #[Route('/modifdocuments/{id}', name: 'modifdocuments')]
    public function modifdocuments(ManagerRegistry $manager, Request $request, int $id): Response
    {   
        $em = $manager->getManager();
        $documents = $this->getDoctrine()->getManager()->getRepository(Documents::class)->find($id);
    
        $form = $this->createForm(modifierdocuments::class, $documents);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($documents);
            $em->flush();
    
            return $this->redirectToRoute('display_blog');
        }
    
        return $this->render('documents/updateDocument.html.twig', ['f' => $form->createView()]);
    }
    #[Route('/AfficheDoc', name: 'AfficheDoc')]
    public function AfficheDoc(): Response
    {         
        $documents =$this->getDoctrine()->getManager()->getRepository(Documents::class)->findAll();
     
        return $this->render('documents/index.html.twig', [
            'd'=>$documents
        ]);
    }

    #[Route('/AfficheDocback', name: 'AfficheDocback')]
    public function AfficheDocback(): Response
    {
       
        $documents =$this->getDoctrine()->getManager()->getRepository(Documents::class)->findAll();
    
        return $this->render('documents/AfficheDoc.html.twig', [
            'd'=>$documents,
          
        ]);
    }


   
    

    

    #[Route('/show_doc/{id}', name: 'show_doc')]
    public function show_doc(Request $request, $id, DocumentsRepository $documentsRepository,QrCodeService $qrcodeService): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Check if $id is a valid integer
        if ($id !== null && ctype_digit($id)) {
            $document = $entityManager->getRepository(Documents::class)->find($id);

            if (!$document) {
                // Handle the case when no document is found for the given id, e.g., redirect or show an error page.
                throw $this->createNotFoundException('Document not found');
            }

            // Increment the view count only if the user has not viewed the document
          

            
             
           
            // Handle the case when $id is not a valid integer, e.g., redirect or show an error page.
         
        }
        $qrCodes = $qrcodeService->qrCode($document);


        return $this->render('documents/qrcode.html.twig', [
            'd' => $document,
          
            'qrCode' => $qrCodes,
        ]);
    }




    #[Route('/search', name: 'search_doc')]
    public function searchAction(Request $request, DocumentsRepository $documentsRepository): Response
    {
        $query = $request->query->get('query');
        
        // Check if a search query is provided
        if ($query) {
            // If a query is provided, perform the search
            $documents = $documentsRepository->searchDocuments($query);
        } else {
            // If no query is provided, fetch all documents
            $documents = $documentsRepository->findAll();
        }

        return $this->render('documents/search_results.html.twig', [
            'd' => $documents,
            'query' => $query,
            
        ]);
    }
  
  
   
   public function sendSms(): Response
   {
       // Vos identifiants Twilio
       $accountSid = 'ACda04b54a2eaf4c114a7be4a307ddb061';
       $authToken = '040b0b8d4cdce68a97c455b8e5bcd875';
       $twilioNumber = '+19805332026';

       // Le numéro de téléphone de destination
       $toPhoneNumber = '+21698566231';

       // Le corps du message SMS
       $messageBody = 'UN NOUVEAU Document A ETE AJOUTE';

       // Créer le client Twilio
       $twilio = new Client($accountSid, $authToken);

       // Envoyer le SMS
       $message = $twilio->messages
           ->create($toPhoneNumber, [
               'from' => $twilioNumber,
               'body' => $messageBody,
           ]);

       // Gérer la réponse ou faire d'autres actions nécessaires

       return new Response('SMS envoyé avec succès!');
   }







   


#[Route('/deleteDocument/{id}', name: 'deleteDocument')]
public function deleteDocument($id, SessionInterface $session, DocumentsRepository $documentsRepository, EntityManagerInterface $entityManager): Response
{
    // Get the authenticated user
    $session = $this->requestStack->getSession();
    $userId = $session->get('User2');

    // Fetch the document
    $document = $documentsRepository->find($id);

    // Check if the document exists
    if (!$document) {
        throw $this->createNotFoundException('Document not found');
    }

    // Check if the authenticated user is the owner of the document
    

    // Remove the document from the database
    $entityManager->remove($document);
    $entityManager->flush();

    // Redirect to 'mylibrary' after successful deletion
    return $this->redirectToRoute('mylibrary');
}


// ...

#[Route('/document_stats', name: 'document_stats')]
public function documentStats(ChartBuilderInterface $chartBuilder, DocumentsRepository $documentsRepository): Response
{
    // Fetch data from the database
    $data = $documentsRepository->getDocumentsCountBySemester();

    // Prepare data for the chart
    $labels = [];
    $datasetData = [];

    foreach ($data as $row) {
        $labels[] = $row['semester'];
        $datasetData[] = $row['document_count'];
    }

    // Create the chart
    $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
    $chart->setData([
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Number of Documents',
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
                'data' => $datasetData,
            ],
        ],
    ]);

    // Render the chart
    return $this->render('documents/stat.html.twig', [
        'chart' => $chart,
    ]);
}
#[Route('/s', name: 's')]
public function yourAction()
    {
       

        return $this->render('documents/stt.html.twig', [
          
        ]);
    }

}






  




   
    
            