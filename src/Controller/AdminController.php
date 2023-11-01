<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @extends AbstractController
 */
#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    /**
     * Display all tasks
     *
     * @param  TaskRepository     $taskRepository     TaskRepository
     * @param  PaginatorInterface $paginatorInterface PaginatorInterface
     * @param  Request            $request            Request
     * @return Response
     */
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/tasks', name: 'tasks_list', methods: ['GET'])]
    public function taskslistAction(TaskRepository $taskRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $data = $taskRepository->findAllOrderedByDate();
        $tasks = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('admin/tasks_list.html.twig', [ 'totalTasks' => $data, 'tasks' => $tasks ]);
    }

    /**
     * Display all users
     *
     * @param  UserRepository     $userRepository     UserRepository
     * @param  PaginatorInterface $paginatorInterface PaginatorInterface
     * @param  Request            $request            Request
     * @return Response
     */
    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/users', name: 'users_list', methods: ['GET'])]
    public function userslistAction(UserRepository $userRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $data = $userRepository->getUsersByDate();
        $users = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/users_list.html.twig', [ 'totalUsers' => $data, 'users' => $users ]);
    }
}
