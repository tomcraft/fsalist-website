<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', EmailType::class, ['empty_data' => $user != null ? $user->getEmail() : ''])
            ->add('subject')
            ->add('message', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $message = (new \Swift_Message($data['subject']))
                    ->setFrom($data['email'])
                    ->setTo('contact@fsalist.com')
                    ->setBody(
                            /*$this->renderView(
                            // templates/emails/registration.html.twig
                                    'emails/registration.html.twig',
                                    ['name' => $name]
                            ),
                            'text/html'*/
                            $data['message'], 'text/plain'
                    );

            $mailer->send($message);

            return $this->redirectToRoute('homepage');
        }


        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
