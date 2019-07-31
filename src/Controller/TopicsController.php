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



/**
 * @Route("/topics")
 */
class TopicsController extends AbstractController
{
    /**
     * @Route("/", name="topics_index")
     */
    public function index(TopicsRepository $topics): Response
    {
        return $this->render('topics/index.html.twig', [
            'topics' => $topics->findAll(),
            // 'topics' => $topics->findBy(['id'], ['dateCreation' => 'ASC']),
            'title'  => 'Topics'
        ]);
    }

    /**
     * @Route("/new", name="topics_new")
     * @Route("/edit/{id}", name="topics_edit")
     */
    public function new_edit(Topics $topics = null , Message $post = null, Request $request, ObjectManager $manager): Response{

        if(!$topics){
            $topics = new Topics;
        }
        

        $form = $this->createForm(TopicsType::class, $topics);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($post){

               $post->setContenu($form->get("firstMessage")->getData()); 
            }
                
            $post = new Message();

            $post->setSujet($topics);

            $topics->setAuteur($this->getUser());
            $post->setAuteur($this->getUser());
            
            $manager->persist($topics);
            $manager->persist($post);
            
            
            $manager->flush();

            return $this->redirectToRoute('topics_index');
        }

        return $this->render('topics/new_edit.html.twig', [
            'editMode' => $topics->getId() !== null ,
            'form'     => $form->createView(),
            'title'    => 'Topics'
        ]);
    }

    /**
     * @Route("/show/{id}", name="topics_show")
     */
    public function show (Topics $topics): Response {

        return $this->render('topics/show.html.twig', [
            'topic' => $topics,
            'title'  => 'Topics'
        ]);
    }

    /**
     * @Route("/delete/{id}", name="topics_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Topics $topics, ObjectManager $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topics->getId(), $request->request->get('_token'))) {
            $entityManager->remove($topics);
            $entityManager->flush();
        }

        return $this->redirectToRoute('topics_index');
    }

    
}
