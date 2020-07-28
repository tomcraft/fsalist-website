<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/profile", name="profile")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage)
    {
        /** @var $user User */
        $user = $this->getUser();
        $detailsForm = $this->createFormBuilder($user)
            ->add('displayName', TextType::class, ['empty_data' => $user->getDisplayName()])
            ->add('email', EmailType::class, ['empty_data' => $user->getEmail()])
            ->add('firstName', TextType::class, ['empty_data' => $user->getFirstName()])
            ->add('lastName', TextType::class, ['empty_data' => $user->getLastName()])
            ->getForm();

        $detailsForm->handleRequest($request);

        if ($detailsForm->isSubmitted() && $detailsForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        $passwordForm = $this->createFormBuilder()
                ->add('password', PasswordType::class)
                ->add('newPassword', PasswordType::class)
                ->add('confirmPassword', PasswordType::class)
                ->getForm();

        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $data = $passwordForm->getData();
            $user = $this->getUser();

            if ($passwordEncoder->isPasswordValid($user, $data['password'])
                && $data['newPassword'] == $data['confirmPassword']) {
                // encode the plain password
                $user->setPassword($passwordEncoder->encodePassword($user, $data['confirmPassword']));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->redirect($request->getUri());
        }

        $deleteAccountForm = $this->createFormBuilder()
                ->add('placeholder', HiddenType::class)
                ->getForm();

        $deleteAccountForm->handleRequest($request);

        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $request->getSession()->invalidate();
            $tokenStorage->setToken();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('profile/index.html.twig', [
            'detailsForm' => $detailsForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'deleteAccountForm' => $deleteAccountForm->createView(),
        ]);
    }

}
