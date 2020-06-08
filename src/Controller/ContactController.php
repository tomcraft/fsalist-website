<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name')
            ->add('email', EmailType::class)
            ->add('subject')
            ->add('message', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            print_r($data);
            // send email

            return $this->redirectToRoute('homepage');
        }


        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
