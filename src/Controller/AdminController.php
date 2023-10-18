<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/tasks", name="admin_tasks_list")
     */    
    /**
     * TaskslistAction
     *
     * @param  TaskRepository $taskRepository TaskRepository
     * @return void
     */
    public function taskslistAction(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAll();

        return $this->render('admin/tasks_list.html.twig',
        [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/admin/users", name="admin_users_list")
     */    
    /**
     * UserslistAction
     *
     * @param  UserRepository $userRepository UserRepository
     * @return void
     */
    public function userslistAction(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users_list.html.twig',
        [
            'users' => $users
        ]);
    }
}
