<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/signin", name="sign-in")
     */
    public function signin()
    {
        return $this->render('auth/signin.html.twig');
    }

    /**
     * @Route("/signup", name="sign-up")
     */
    public function signup()
    {
        return $this->render('auth/signup.html.twig');
    }

    /**
     * @Route("/forgot", name="forgot-password")
     */
    public function forgot()
    {
        return $this->render('auth/forgot.html.twig');
    }

}