<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Import the Request class
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Topic; // Make sure to import the Topic entity
use App\Form\TopicType; 


class TopicController extends AbstractController
{ #[Route('/topic', name: 'display_topic')]
    public function index(): Response
    {
        $topic = $this->getDoctrine()->getManager()->getRepository(Topic::class)->findAll();
        
        return $this->render('topic/index.html.twig', [
            't' => $topic,
        ]);
    }
    #[Route('/addtopic', name: 'app_addtopic')]
    public function addtopic(Request $request): Response
    {
        $topic = new Topic();
        
        $entityManager = $this->getDoctrine()->getManager();
         $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
      


            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();
    
    
          return $this->redirectToRoute('display_topic');
        }

        return $this->render('topic/createtopic.html.twig', ['f' => $form->createView()]);
    }

    #[Route("/removedoc/{iddoc}", name: 'supp_doc', methods: ['GET'])]
    public function suppressiondocuments(Documents $documents): Response
    {
       
        $em = $this->getDoctrine()->getManager();
        $em->remove($documents);
        $em->flush();
        return $this->redirectToRoute('display_blog');


    }
    #[Route('/modifdocuments/{iddoc}', name: 'modifdoc')]
    public function modifdocuments(Request $request, $iddoc): Response
    {
        $documents = $this->getDoctrine()->getManager()->getRepository(Documents::class)->find($iddoc);
    
        $form = $this->createForm(modifierdocuments::class, $documents);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documents);
            $em->flush();
    
            return $this->redirectToRoute('display_topic');
        }
    
        return $this->render('documents/updateDocument.html.twig', ['f' => $form->createView()]);
    }
    

}
   




