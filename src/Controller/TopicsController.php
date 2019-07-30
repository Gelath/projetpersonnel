<?php

namespace App\Controller;

use App\Entity\Topics;
use App\Entity\Message;
use App\Form\TopicsType;
use App\Repository\TopicsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicsController extends AbstractController
{
    /**
     * @Route("/topics", name="topics_index")
     */
    public function index(TopicsRepository $topics): Response
    {
        return $this->render('topics/index.html.twig', [
            'topics' => $topics->findAll(),
            'title'  => 'Topics'
        ]);
    }
    
    /**
     * @Route("topics/edit/{id}", name="topics_edit", methods={"GET"})
     */
    public function edit(Topics $topics, Request $request, ObjectManager $manager): Response {


        $form = $this->createForm(TopicsType::class, $topics);
        $form->handleRequest($request);

        $form->remove('firstMessage');

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->flush();

            return $this->redirectToRoute('topics_index');
        }

        return $this->render('topics/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Topics'
        ]);
    }

    /**
     * @Route("topics/new", name="topics_new")
     */
    public function new(Request $request, ObjectManager $manager): Response{

        $topics = new Topics;

        $form = $this->createForm(TopicsType::class, $topics);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = new Message();

            $post->setContenu($form->get("firstMessage")->getData());
            $post->setSujet($topics);

            $topics->setAuteur($this->getUser());
            $post->setAuteur($this->getUser());
            
            $manager->persist($topics);
            $manager->persist($post);
            
            $manager->flush();

            return $this->redirectToRoute('topics_index');
        }

        return $this->render('topics/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Topics'
        ]);
    }

    
}
