<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\AppAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class WatchlistController extends AbstractController
{

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/watchlist", name="watchlist")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        /** @var $user User */
        $user = $this->getUser();


        return $this->render('base.html.twig', [
        ]);
    }
}
