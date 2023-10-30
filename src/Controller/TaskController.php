<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @extends AbstractController
 */
class TaskController extends AbstractController
{
    /**
     * Get tasks list
     *
     * @param  TaskRepository     $taskRepository     TaskRepository
     * @param  User               $user2               User
     * @param  PaginatorInterface $paginatorInterface PaginatorInterface
     * @param  Request            $request            Request
     * @return Response
     */
    #[Route('/user/{id}/tasks', name: 'task_list', methods: ['GET'])]
    #[Security("is_granted('ROLE_USER') and user === user2 || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour afficher cette page')]
    public function listAction(TaskRepository $taskRepository, User $user2, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $data = $taskRepository->findByUser($user2);
        $tasks = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('task/list.html.twig',
        [
            'tasks' => $tasks
        ]);
    }

    /**
     * Get tasks done list
     *
     * @param  TaskRepository     $taskRepository     TaskRepository
     * @param  User               $user2               User
     * @param  PaginatorInterface $paginatorInterface PaginatorInterface
     * @param  Request            $request            Request
     * @return Response
     */
    #[Route('/user/{id}/done', name: 'done_task_list', methods: ['GET'])]
    #[Security("is_granted('ROLE_USER') and user === user2 || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour afficher cette page')]
    public function doneListAction(TaskRepository $taskRepository, User $user2, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $data = $taskRepository->doneTasksByUser($user2);
        $tasks = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('task/list.html.twig',
        [
            'tasks' => $tasks
        ]);
    }



    /**
     * Create a task
     *
     * @param  Request        $request        Request
     * @param  TaskRepository $taskRepository TaskRepository
     * @return Response
     */
    #[Route('/task/create', name: 'task_create', methods: ['GET', 'POST'])]
    public function createAction(Request $request, TaskRepository $taskRepository): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskFormType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedBy($this->getUser());
            $task->setCreatedAt(new \DateTime());
            $task->setIsDone(0);

            $task = $form->getData();
            $taskRepository->save($task, true);

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list', ['id' => $task->getCreatedBy()->getId()]);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Show a task
     *
     * @param  Task                   $task           Task
     * @param  Request                $request        Request
     * @param  EntityManagerInterface $entityManager  EntityManager
     * @return Response
     */
    #[Route('/task/{id}', name: 'task_show')]
    #[Security("is_granted('ROLE_USER') and user === task.getCreatedBy() || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour afficher cette page')]
    public function figure(?Task $task): Response
    {
        if (!$task) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('task/show.html.twig', ['task' => $task ]);
    }

    /**
     * Edit a task
     *
     * @param  Task           $task           Task
     * @param  Request        $request        Request
     * @param  TaskRepository $taskRepository TaskRepository
     * @return Response
     */
    #[Route('/task/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === task.getCreatedBy() || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour afficher cette page')]
    public function editAction(Task $task, Request $request, TaskRepository $taskRepository): Response
    {
        $form = $this->createForm(TaskFormType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUpdatedBy($this->getUser());
            $task = $form->getData();
            $taskRepository->save($task, true);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list', ['id' => $task->getCreatedBy()->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * Finished a task
     *
     * @param  Tast           $task           Task
     * @param  TaskRepository $taskRepository TaskRepository
     * @return Response
     */
    #[Route('/task/{id}/toggle', name: 'task_toggle', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === task.getCreatedBy() || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour cette action')]
    public function toggleTaskAction(Task $task, TaskRepository $taskRepository): Response
    {
        $task->setIsDone(!$task->isDone());

        $taskRepository->save($task, true);

        $this->addFlash('success', $task->isDone() ? sprintf('La tâche %s a bien été marquée : faite.', $task->getTitle()) : sprintf('La tâche %s a bien été marquée : à faire.', $task->getTitle()));

        return $this->redirectToRoute('task_list', ['id' => $this->getUser()->getId()]);
    }

    /**
     * Delete a task
     *
     * @param  Task           $task           Task
     * @param  TaskRepository $taskRepository TaskRepository
     * @return Response
     */
    #[Route('/task/{id}/delete', name: 'task_delete', methods: ['GET', 'DELETE'])]
    #[Security("is_granted('ROLE_USER') and user === task.getCreatedBy() || is_granted('ROLE_ADMIN')", message: 'Vous n\'avez pas les droits suffisants pour cette action')]
    public function deleteTaskAction(Task $task, TaskRepository $taskRepository): Response
    {
        $taskRepository->remove($task, true);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list', ['id' => $this->getUser()->getId()]);
    }
}
