<?php

namespace App\Controller;
use App\Entity\Raiting;
use App\Entity\Documents;
use App\Form\RatingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\User2Repository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RatingController extends AbstractController
{  private $requestStack;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,RequestStack $requestStack)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }
   
    #[Route('/rate/{id}', name: 'app_rate')]
    public function rateAction(Request $request, Documents $document,User2Repository $userRepository): Response
    {
        $rating = new Raiting();
        $session = $this->requestStack->getSession();
        $userId = $session->get('User2'); //
    
        $rating->setIduser($userId);
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setId($document);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rating);
            $entityManager->flush();

            return $this->redirectToRoute('AfficheDoc', ['id' => $document->getId()]);
        }

        return $this->render('documents/rating_form.html.twig', [
            'form' => $form->createView(),
            'd' => $document,
        ]);
    }
}
