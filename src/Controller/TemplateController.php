<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{
    #[Route('/template', name: 'apptemplate')]
    public function index(PostRepository $postRepository): Response
    {
        //$mostCommentedPost = $postRepository->findPostWithMostComments();
        return $this->render('template/index.html.twig', [
            'controller_name' => 'TemplateController',
            //'stat'=> $mostCommentedPost,

        ]);
    }
    #[Route('/templateback', name: 'apptemplateback')]
    public function indexback(): Response
    {
        return $this->render('template/indexback.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

    #[Route('/appfirstindex', name: 'appfirstindex')]
    public function firstindex(PostRepository $postRepository): Response
    {
        //$mostCommentedPost = $postRepository->findPostWithMostComments();
        return $this->render('template/firstindex.html.twig', [
            'controller_name' => 'TemplateController',
            //'stat'=> $mostCommentedPost,
        ]);
    }
    #[Route('/aboutus', name: 'aboutus')]
    public function aboutus(): Response
    {
        return $this->render('template/Aboutus.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }


}
