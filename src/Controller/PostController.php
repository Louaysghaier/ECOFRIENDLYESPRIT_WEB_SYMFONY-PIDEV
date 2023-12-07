<?php

namespace App\Controller;

use Twilio\Rest\Client;
use App\Entity\Post;
use App\Entity\User2;
use App\Form\PostType;
use App\Form\SearchType;
use App\Repository\PostRepository;
use App\Bundle\Notification;
use App\Bundle\BadWordsFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Notifier\NotifierInterface;
use Knp\Component\Pager\PaginatorInterface; 
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PostController extends AbstractController
{
    private $requestStack;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RequestStack $requestStack)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }

    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/addPost', name: 'addPost')]
    public function addPost(ManagerRegistry $manager, Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2'); // Assurez-vous que 'userId' est la clé correcte

        // Récupérer l'utilisateur depuis la base de données
        $user = $this->getDoctrine()->getRepository(User2::class)->find($userId);

        $em = $manager->getManager();
        //$user = $this->getUser();
        $post = new Post();
        
        $form = $this->createForm(PostType::class,$post,[
            //'user' => $user,
          
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $uploadedFile = $form->get('imagePost')->getData();
    
            if ($uploadedFile) {
                $imageDirectory = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
    
                try {
                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/';
                    $uploadedFile->move($destination, $newFilename);
                } catch (FileException $e) {
                    // Handle the file upload exception
                }
    
                $post->setImagePost('uploads/images/'.$newFilename);
            }
            //$id=$user->getIduser();
            $post->setIdUser($user);
            $badWordsList=['fuck','Bollocks','Bugger','Asshole','Shit','Fuck','bitch','Dick','Tits','Munter',
                        'Motherfucker','Bastard','Damn','Bloody','Arse','Bullshit','Pissed','Cunt','Cow',
                        'Sod','ass','arsehead','arsehole','brotherfucker','child-fucker','cock','cocksucker',
                        'crap','cunt','dickhead','dyke','fatherfucker','frigger','goddamn','godsdamn','hell',
                        'shit','2g1c','2 girls 1 cup','acrotomophilia','anal','anus','apeshit','DISABLEDass',
                        'assmunch','autoerotic','acrotomophilia','sucking','sack','bangbros','bareback','bastard',
                        'bastardo','bastinado','beaner','beaners','birdlock','bitches','cock','blowjob','blow job',
                        'blow your load','boob','boobs','bukkake','bulldyke','bullshit','bunghole','bung hole',
                        'camgirl','camslut','cocks','dirty pillows','dildo','dirty sanchez','doggiestyle',
                        'doggie style','doggy style','doggystyle','dog style','dolcett','dommes', 'ass','squirting',
                        'femdom','fingerbang','fisting','footjob','foot fetish','fuckin', 'fucking','fucktards',
                        'fudgepacker','gang bang','gay sex','goregasm','g-spot','hand job','handjob','hard core', 
                        'hardcore','milf','nipples','nipple','nigga', 'nigger','milf','nude','nudity','nympho',
                        'orgasm','paedophile','pegging','pedophile', 'penis','sex','pissing','playboy','porn',
                        'porno','pornography', 'pubes','pussy','cowgirl','slut','snatch','sucks','swastika',
                        'swinger', 'threesome','vagina'
            ];
            $postContent = $post->getDescriptionPost();
            $postTitle = $post->getTitle();
            foreach ($badWordsList as $badword) {
                if (stripos($postTitle, $badword) !== false) {
                    $this->addFlash('danger', 'The Post has BadWords.');
                    return $this->redirectToRoute('addPost');
                }
            }
            foreach ($badWordsList as $badword) {
                if (stripos($postContent, $badword) !== false) {
                    $this->addFlash('danger', 'The Post has BadWords.');
                    return $this->redirectToRoute('addPost');
                }
            }
            $post->setNbresComments(0);
            $post->prePersist();
            //$post->setNbresComments(getCommentCount());
            //$post = $form->getData();
            //$post->setIduser($user);

            /*$notification = new Notification();
            $notification->setMessage('New Post added check it!');*/

            $em->persist($post);  
            $em->flush();
            //$this->sendSms();
            //$notifier->send(new Notification('New Post Added Successfuly, Check it Now!.', ['browser']));

            $this->addFlash('success', 'Added Successfuly!');

            return $this->redirectToRoute('SaleandExchange');
        }
        return $this->render("post/addPost.html.twig",[
         'form'=>$form->createView()
         ]);     
    }

    #[Route('/Readpost', name: 'Readpost')]
    public function ReadPost(): Response
    {
        $posts =$this->getDoctrine()->getManager()->getRepository(Post::class)->findAll();
        return $this->render('post/ReadPost.html.twig', [
            'p'=>$posts
        ]);
    }

    #[Route('/deletePost/{id}', name: 'deletePost')]
public function deletePost($id, PostRepository $postRepository, EntityManagerInterface $entityManager ): Response
{
    $session = $this->requestStack->getSession();
    $userId = $session->get('User2');
  
    $entityManager = $this->getDoctrine()->getManager();
    $post = $entityManager->getRepository(Post::class)->find($id);

    if (!$post) {
        throw $this->createNotFoundException('Comment not found');
    }
    $comments = $post->getComments();

    // Supprimer manuellement les commentaires associés
    foreach ($comments as $comment) {
        $entityManager->remove($comment);
    }

    $entityManager->remove($post);
    $entityManager->flush();
    
    $this->addFlash('success', 'Post deleted successfully');

    // Rediriger vers une route qui n'utilise pas la variable 'post'
    return $this->redirectToRoute('myposts');

}

    
    #[Route('/updatePost/{postId}', name: 'updatePost')]
    public function updatePost(ManagerRegistry $manager, Request $request, $postId): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2');
        $user = $this->getDoctrine()->getRepository(User2::class)->find($userId);
        $em = $manager->getManager();
        //$user = $this->getUser();
        $post =$this->getDoctrine()->getManager()->getRepository(Post::class)->find($postId);
    
        $form = $this->createForm(PostType::class,$post,/*[
            'user' => $user,
        ]*/);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $uploadedFile = $form->get('imagePost')->getData();
    
            if ($uploadedFile) {
                $imageDirectory = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
    
                try {
                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/';
                    $uploadedFile->move($destination, $newFilename);
                } catch (FileException $e) {
                    // Handle the file upload exception
                }
    
                $post->setImagePost('uploads/images/'.$newFilename);
            }
            $post->setIdUser($user);
            $post->prePersist();
            //$post->setIduser($user);

            $em->flush();

            return $this->redirectToRoute('myposts');
        }
        return $this->render("post/updatePost.html.twig",[
         'form'=>$form->createView()
         ]);

        
    }
    #[Route('/SaleandExchange', name: 'SaleandExchange')]
    public function SaleandExchange(Request $request, PaginatorInterface $paginator): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2');
        //$user = $this->getDoctrine()->getRepository(User2::class)->find($userId);
        $post =$this->getDoctrine()->getManager()->getRepository(Post::class)->findBy(
            ['subject' => 'Sale & Exchange'],
            ['dateCreationPost' => 'DESC']);
        $posts = $paginator->paginate(
            $post, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('post/SaleandExchange.html.twig', [
            'p'=>$posts,
            'user'=>$userId,
        ]);
    }
    #[Route('/CodingProblems', name: 'CodingProblems')]
    public function CodingProblems(Request $request, PaginatorInterface $paginator): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2');
        $post =$this->getDoctrine()->getManager()->getRepository(Post::class)->findBy(
            ['subject' => 'Coding Problems'],
            ['dateCreationPost' => 'DESC']);
        $posts = $paginator->paginate(
            $post, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render('post/CodingProblems.html.twig', [
            'p'=>$posts
        ]);
    }
    #[Route('/EspritProblems', name: 'EspritProblems')]
    public function EspritProblems(Request $request, PaginatorInterface $paginator): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2');
        $post =$this->getDoctrine()->getManager()->getRepository(Post::class)->findBy(
            ['subject' => 'Esprit Problems'],
            ['dateCreationPost' => 'DESC']);
        $posts = $paginator->paginate(
            $post, 
            $request->query->getInt('page', 1), 
            4 // Nombre de résultats par page
        );
        return $this->render('post/EspritProblems.html.twig', [
            'p'=>$posts
        ]);
    }
    #[Route('/myforum', name: 'myforum')]
    public function myforum(): Response
    {
        return $this->render('post/myforum.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/myposts', name: 'myposts')]
    public function myposts(Request $request, PaginatorInterface $paginator): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2');
        //$userId = $this->getUser()->getId();

        // Récupérer les posts de l'utilisateur depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $userPosts = $entityManager->getRepository(Post::class)->findBy(['idUser' => $userId]);

        return $this->render('post/myposts.html.twig', [
            'userPosts' => $userPosts,
        ]);
    }
    #[Route('/ReadPostBack', name: 'ReadPostBack')]
    public function ReadPostBack(): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2');
        $posts =$this->getDoctrine()->getManager()->getRepository(Post::class)->findAll();
        return $this->render('post/ReadPostBack.html.twig', [
            'p'=>$posts
        ]);
    }

    public function sendSms(): Response
    {
        // Vos identifiants Twilio
        $accountSid = 'ACc8429eaea9ff046e939f02bde88a3a6c';
        $authToken = 'd15716252951e6941578f55df225a29f';
        $twilioNumber = '+17346362801';

        // Le numéro de téléphone de destination
        $toPhoneNumber = '+21625285386';

        // Le corps du message SMS
        $messageBody = 'Admin!, Check the new Post added!';

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

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, PostRepository $postRepository ): Response
    {
        $searchTerm = $request->query->get('query');
        dump($searchTerm);
            if ($searchTerm) {
                $posts = $postRepository->findByTitle($searchTerm);
                return $this->render('post/search.html.twig', [
                    'posts' => $posts,
                    'searchTerm' => $searchTerm,
                ]);
                
            } else {
                //echo("Pas de Post with this Title");
                $this->addFlash('danger', 'No Post with this Title!');
                return $this->redirectToRoute('SaleandExchange');
            }
            
    }
    #[Route('/statistiques', name: 'statistiques')]
    public function Statistique(PostRepository $postRepository): Response
    {
        $stat = $postRepository->findPostWithMostComments();

        return $this->render('template/index.html.twig', [
            'stat' => $stat,
        ]);
    }
    
}
