<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{    
    /**
     * @Route("/user/create", name="user_create")
     *
     * CreateAction
     *
     * @param  Request                     $request            Request
     * @param  UserPasswordHasherInterface $UserPasswordHasher UserPasswordHasherInterface
     * @return Response
     */
    public function createAction(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //$this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('login');
        }

        return $this->render('user/create.html.twig', [
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
     * @param  UserPasswordHasherInterface $UserPasswordHasher UserPasswordHasherInterface
     * @return Response
     */
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('task_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
