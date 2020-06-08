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

class ProfileController extends AbstractController
{
    /** @var UserPasswordEncoderInterface $passwordEncoder */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/profile", name="profile")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request)
    {
        /** @var $user User */
        $user = $this->getUser();
        $detailsForm = $this->createFormBuilder($user)
            ->add('displayName', TextType::class, ['empty_data' => $user->getDisplayName()])
            ->add('email', EmailType::class, ['empty_data' => $user->getEmail()])
            ->add('firstName', TextType::class, ['empty_data' => $user->getFirstName()])
            ->add('lastName', TextType::class, ['empty_data' => $user->getLastName()])
            ->getForm();

        $passwordForm = $this->createFormBuilder()
            ->add('password', PasswordType::class)
            ->add('newPassword', PasswordType::class)
            ->add('confirmPassword', PasswordType::class)
            ->getForm();

        $detailsForm->handleRequest($request);

        if ($detailsForm->isSubmitted() && $detailsForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $data = $passwordForm->getData();
            $user = $this->getUser();

            if ($this->passwordEncoder->isPasswordValid($user, $data['password'])
                && $data['newPassword'] == $data['confirmPassword']) {
                // encode the plain password
                $user->setPassword($this->passwordEncoder->encodePassword($user, $data['confirmPassword']));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->redirect($request->getUri());
        }

        return $this->render('profile/index.html.twig', [
            'detailsForm' => $detailsForm->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
}
