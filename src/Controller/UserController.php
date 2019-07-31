<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route ("/user)
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index")
     */
    public function index(UserRepository $user): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $user->findAll(),
            'title'  => 'Utilisateur'
        ]);
    }
}
