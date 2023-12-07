<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User2;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CommentController extends AbstractController
{
    private $requestStack;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RequestStack $requestStack)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }
    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /*#[Route('/addComment/{postId}', name: 'addComment')]
    public function addComment(ManagerRegistry $manager, Request $request, $postId): Response
    {
        $em = $manager->getManager();
        //$user = $this->getUser();
        $comment = new Comment();
        $post = new Post();
        $form = $this->createForm(CommentType::class,$comment,/*[
            'user' => $user,
        ]*//*);
        $form->handleRequest($request);
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepository->findBy(['idPost' => $postId]);
        $nbr->getNbresComments();
        $nbrre = $nbr +1;
        if($form->isSubmitted() && $form->isValid())
        {
        
            $comment->setIdPost($postId);
            $comment->prePersist();
            $post->setNbresComments($nbrre);
            //$comment->setIduser($user);
            
            $em->persist($comment);  //add
            $em->flush();

            return $this->redirectToRoute('SaleandExchange');
        }
        return $this->render("comment/addComment.html.twig",[
            'c'=>$comment,
            'postId' => $postId,
         'form'=>$form->createView(),
         ]);

        
    }*/
    #[Route('/addComment/{postId}', name: 'addComment')]
    public function addComment(ManagerRegistry $manager, Request $request, $postId): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2'); // Assurez-vous que 'userId' est la clé correcte

        // Récupérer l'utilisateur depuis la base de données
        $user = $this->getDoctrine()->getRepository(User2::class)->find($userId);
        $em = $manager->getManager();

        // Récupérer le post associé
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepository->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        // Récupérer le nombre actuel de commentaires
        $nbr = $post->getNbresComments();
        $nbrre = $nbr + 1;

        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisez directement le service BadWords injecté
            
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
            $commentContent = $comment->getDescriptionComment();
            foreach ($badWordsList as $badword) {
                if (stripos( $commentContent, $badword) !== false) {
                    $this->addFlash('danger', 'The Comment has BadWords.');
                    return $this->redirectToRoute('addComment', ['postId' => $postId]);
                }
            }
            $comment->setIdUser($user);
            $comment->setIdPost($postId);
            $comment->prePersist();

            // Ajouter le commentaire au post
            //$post->addComment($comment);

            // Mettre à jour le nombre de commentaires dans le post
            $post->setNbresComments($nbrre);

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('SaleandExchange');
        }

        return $this->render("comment/addComment.html.twig", [
            'c' => $comment,
            'postId' => $postId,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/Readcomment', name: 'Readcomment')]
    public function ReadComment(): Response
    {
        $comments =$this->getDoctrine()->getManager()->getRepository(Comment::class)->findAll();
        return $this->render('Comment/ReadComment.html.twig', [
            'c'=>$comments
        ]);
    }
    #[Route('/Readcommentfront/{postId}', name: 'Readcommentfront')]
    public function readCommentsfront($postId): Response
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepository->findBy(['idPost' => $postId]);

        return $this->render('comment/ReadCommentFront.html.twig', [
            'c' => $comments,
        ]);
    }
    /*public function ReadCommentfront($postId, CommentRepository $commentRepository): Response
    {
        $comments =$this->getDoctrine()->getManager()->getRepository(Comment::class)->find($postId);
        return $this->render('Comment/ReadCommentFront.html.twig', [
            'c'=>$comments
        ]);
    }*/

    #[Route('/Deletecomment/{id}', name: 'Deletecomment')]
public function DeleteComment(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $comment = $entityManager->getRepository(Comment::class)->find($id);

    if (!$comment) {
        throw $this->createNotFoundException('Comment not found');
    }

    $entityManager->remove($comment);
    $entityManager->flush();

    return $this->redirectToRoute('mycomments');
}
    #[Route('/EditComment/{id}', name: 'EditComment')]
    public function EditPost(ManagerRegistry $manager, Request $request, $id): Response
    {
        $em = $manager->getManager();
        //$user = $this->getUser();
        $comment =$this->getDoctrine()->getManager()->getRepository(Comment::class)->find($id);
    
        $form = $this->createForm(CommentType::class,$comment,/*[
            'user' => $user,
        ]*/);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $comment->prePersist();
            //$comment->setIduser($user);

            $em->flush();

            return $this->redirectToRoute('mycomments');
        }
        return $this->render("comment/updateComment.html.twig",[
         'form'=>$form->createView()
         ]);

        
    }
    #[Route('/mycomments', name: 'mycomments')]
    public function mycomments(Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2');
        //$userId = $this->getUser()->getId();

        // Récupérer les posts de l'utilisateur depuis la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $userComments = $entityManager->getRepository(Comment::class)->findBy(['idUser' => $userId]);

        return $this->render('comment/mycomments.html.twig', [
            'c' => $userComments,
        ]);
    }
    
}
