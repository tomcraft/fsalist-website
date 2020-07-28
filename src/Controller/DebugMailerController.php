<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DebugMailerController
 * @package App\Controller
 */
class DebugMailerController extends AbstractController
{
    /**
     * @Route("/mailer/register", name="mailer-register")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->render('auth/register-mail.html.twig', [
                "username" => 'totolastiko'
        ]);
    }

}