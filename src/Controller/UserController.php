<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Form\UserEditFormType;
use App\Form\PasswordEditFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends AbstractController
 */
class UserController extends AbstractController
{
    /**
     * Create a user
     *
     * @param  Request                     $request            Request
     * @param  UserPasswordHasherInterface $userPasswordHasher UserPasswordHasherInterface
     * @param  UserRepository              $userRepository     UserRepository
     * @return Response
     */
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function createAction(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $user = $form->getData();
            $userRepository->save($user, true);

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit a user
     *
     * @param  User           $user2          User
     * @param  Request        $request        Request
     * @param  UserRepository $userRepository UserRepository
     * @return Response
     */
    #[Route('/user/{id}/edit', name: 'user_edit', methods: ['GET', 'PATCH'])]
    #[Security("is_granted('ROLE_USER') and user === user2 || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour afficher cette page')]
    public function editAction(User $user2, Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserEditFormType::class, $user2);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user2 = $form->getData();
            $userRepository->save($user2, true);

            $this->addFlash('success', "Le profil a bien été modifié");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user2]);
    }

    /**
     * Edit user password
     *
     * @param  User                        $user2              User
     * @param  Request                     $request            Request
     * @param  UserPasswordHasherInterface $UserPasswordHasher UserPasswordHasherInterface
     * @param  UserRepository              $userRepository     UserRepository
     * @return Response
     */
    #[Route('/user/{id}/edit/password', name: 'user_edit_password', methods: ['GET', 'PATCH'])]
    #[Security("is_granted('ROLE_USER') and user === user2 || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour afficher cette page')]
    public function passwordEditAction(User $user2, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $form = $this->createForm(PasswordEditFormType::class, $user2);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $userPasswordHasher->hashPassword($user2, $user2->getPassword());
            $user2->setPassword($password);

            $user2 = $form->getData();
            $userRepository->save($user2, true);

            $this->addFlash('success', "Le mot de passe a bien été modifié");
            
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/passwordEdit.html.twig', ['form' => $form->createView(), 'user' => $user2]);
    }
}
