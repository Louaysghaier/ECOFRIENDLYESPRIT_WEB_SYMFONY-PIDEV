<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User2;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/block-user/{id}', name: 'admin_block_user')]
    public function blockUser(User2 $user, Request $request)
    {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user->setIsBlocked(!$user->getIsBlocked());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
    }
}
