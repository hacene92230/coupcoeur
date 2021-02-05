<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Security\LoginAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/account/{id}", name="user_show", methods={"GET"})
     */
    public function userShow(User $user): Response
    {
        if ($this->getUser() === $user) {
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        } else {
            return $this->render('user/erreur.html.twig', [
                'user' => $user,
            ]);
        }
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function userEdit(UserPasswordEncoderInterface $encoder, Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($this->getUser() == $user) {
            if ($form->isSubmitted() && $form->isValid()) {
                //Permet de hashé le mot de passe
                $mdp = $encoder->encodePassword($user, $form->get('plainPassword')->getData());
                //Envoi le mot de passe hashé                
                $user->setPassword($mdp);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('home');
            }

            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->render('user/erreur.html.twig', [
                'user' => $user,
            ]);
        }
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/new", name="user_new")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $user->setRoles(['ROLE_CLIENT']);
            $this->addFlash('success', 'votre inscription a bien été prise en compte.');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
