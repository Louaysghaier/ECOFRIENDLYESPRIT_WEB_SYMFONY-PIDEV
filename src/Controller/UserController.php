<?php

namespace App\Controller;
use App\Repository\User2Repository;
use App\Entity\User2;
use App\Form\loginType;
use App\Form\User2Type;
use App\Form\SearchUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Swift_Mailer;
use Swift_Message;
use App\Form\ForgetpasswortType;







class UserController extends AbstractController
{


    private $passwordEncoder;
    private $requestStack;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RequestStack $requestStack)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }
    #[Route('/adduser', name: 'add_user')]
    public function adduser(Request $request): Response
    {
        $user = new User2();
    
        $form = $this->createForm(User2Type::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a user with the same email already exists
            $existingUser = $this->getDoctrine()->getRepository(User2::class)->findOneBy(['mailuser' => $user->getMailuser()]);
    
            if ($existingUser) {
                $this->addFlash('danger', 'User with the provided email already exists.');
                return $this->redirectToRoute('add_user');
            }
    
            $plainPassword = $user->getMdpuser();
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setMdpuser($encodedPassword);
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
    
            $this->addFlash('message', 'User successfully signed up!');
            return $this->redirectToRoute('app_user_login');
        }
    
        return $this->render('user/adduser.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app_user_login')]
    public function login(Request $request, User2Repository $userRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {   $session = $this->requestStack->getSession();
        $form = $this->createForm(loginType::class);
        $form->handleRequest($request);
        //$session->invalidate();

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('mailuser')->getData();
            $password = $form->get('mdpuser')->getData();

            $user = $userRepository->findOneBy(['mailuser' => $email]);

            //if ($user && $passwordEncoder->isPasswordValid($user, $password)) {
            if($user){
                if ($user->getIsblocked()) {
                    $this->addFlash('danger', 'Votre compte est bloqué. Veuillez contacter l\'administrateur.');
                } else {
                    $session->set('User2', $user);

                    // Log user data to the console
                    $this->addFlash('success', 'Login successful. Check the console for user data.');

                    return $this->redirectToRoute('user_profile+', ['id' => $user->getIduser()]);
                }
            } else {
                $this->addFlash('error', 'Invalid credentials');
            }
        }

        return $this->render('user/Loginuser.html.twig', [
            'f' => $form->createView()
        ]);
    }





    #[Route('/appuser', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User2::class)->findAll();

        
        return $this->render('user/index.html.twig', ['b'=>$user ]);
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User2 $user, User2Repository $userRepository): Response
    {   $session = $this->requestStack->getSession();
        $form = $this->createForm(User2Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Assuming save() method persists the changes to the database
            $userRepository->save($user);
            $session->set('User2', $user);
            $this->addFlash('success', 'User updated successfully.');

            // Redirect to the user profile
            return $this->redirectToRoute('user_profile', ['id' => $user->getIduser()]);
        }

        return $this->render('user/updateuser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/forgot', name: 'forgot')]
    public function forgotPassword(Request $request, User2Repository $userRepository,Swift_Mailer $mailer, TokenGeneratorInterface  $tokenGenerator)
    {

        //$session = $this->requestStack->getSession();
        $form = $this->createForm(ForgetpasswortType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();


            $user = $userRepository->findOneBy(['mailuser'=>$donnees]);
            if(!$user) {
                $this->addFlash('danger','cette adresse n\'existe pas');
                return $this->redirectToRoute("forgot");

            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManger = $this->getDoctrine()->getManager();
                $entityManger->persist($user);
                $entityManger->flush();




            }catch(\Exception $exception) {
                $this->addFlash('warning','une erreur est survenue :'.$exception->getMessage());
                return $this->redirectToRoute("app_user_login");


            }

            //$session->invalidate();
            $url = $this->generateUrl('app_reset_password',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);

          
            $message = (new Swift_Message('Mot de password oublié'))
                ->setFrom('louay.sghaier@esprit.tn')
                ->setTo($user->getmailuser())
                ->setBody("<p> Bonjour</p> unde demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant :".$url,
                "text/html");

           
            $mailer->send($message);
            $this->addFlash('message','E-mail  de réinitialisation du mp envoyé :');
        //    return $this->redirectToRoute("app_login");

        }

        return $this->render("user/forgotpassword.html.twig",['form'=>$form->createView()]);
    }
     #[Route('/resetpassword/{token}', name: 'app_reset_password')]
    public function resetpassword(Request $request,string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User2::class)->findOneBy(['reset_token'=>$token]);

        if($user == null ) {
            $this->addFlash('danger','TOKEN INCONNU');
            return $this->redirectToRoute("app_user_login");

        }

        if($request->isMethod('POST')) {
            $user->setResetToken(null);
        
            $plainPassword = $user->getMdpuser();
            $user->setmdpuser($passwordEncoder->encodePassword($user,$plainPassword));
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();

            $this->addFlash('message','Mot de passe mis à jour :');
            return $this->redirectToRoute("app_user_login");

        }
        else {
            return $this->render("user/resetPassword.html.twig",['token'=>$token]);

        }
    }
    #[Route('/admin/block-user/{id}', name: 'admin_block_user')]
    public function blockUser(User2 $user, Request $request)
    {
     
        $user->setIsblocked(!$user->getisblocked());

        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        
        return $this->redirectToRoute('app_user', ['id' => $user->getIduser()]);
    }
    #[Route('/tirer', name: 'tirerusers')]
    public function yourAction(User2Repository $userRepository): Response
    {
        $user = $userRepository->findAllAlphabeticalOrder();

      

        return $this->render('user/index.html.twig', [
            'b' => $user,
        ]);
    }
    #[Route('/searchuser', name: 'searchuser')]
    public function searchAction(Request $request, User2Repository $userRepository): Response
    {
        $user = $request->request->get('nomuser');

        if ($user) {
            $users = $userRepository->searchusers($user);
        } else {
            
            $users = $userRepository->findAll();
        }
    
        return $this->render('user/search_results.html.twig', [
            'users' => $users,  
        ]);
    }
    #[Route('/profile/{id}', name: 'user_profile')]
    public function userProfileAction($id, User2Repository $userRepository, Request $request): Response
    {    $session = $this->requestStack->getSession();
        $user = $session->get('User2') ;       // Check if the user is authenticated
        if (!$user) {
            // Handle the case where the user is not authenticated
            $this->addFlash('error', 'You are not logged in.');
            return $this->redirectToRoute('app_user_login');
        }
        return $this->render('user/afficheuser.html.twig', [
            'user' => $user,
        ]);
    }


}

