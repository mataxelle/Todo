<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Form\UserEditFormType;
use App\Form\PasswordEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{    
    /**
     * @Route("/register", name="register")
     *
     * CreateAction
     *
     * @param  Request                     $request            Request
     * @param  UserPasswordHasherInterface $UserPasswordHasher UserPasswordHasherInterface
     * @param  EntityManagerInterface      $entityManager      EntityManager
     * @return Response
     */
    public function createAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     *
     * EditAction
     *
     * @param  User                        $user               User
     * @param  Request                     $request            Request
     * @param  EntityManagerInterface      $entityManager      EntityManager
     * @return Response
     */
    public function editAction(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserEditFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Le profil a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/user/{id}/edit/password", name="user_edit_password")
     *
     * PasswordEditAction
     *
     * @param  User                        $user               User
     * @param  Request                     $request            Request
     * @param  UserPasswordHasherInterface $UserPasswordHasher UserPasswordHasherInterface
     * @param  EntityManagerInterface      $entityManager      EntityManager
     * @return Response
     */
    public function passwordEditAction(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PasswordEditFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Le mot de passe a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/passwordEdit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
