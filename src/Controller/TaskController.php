<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{    
    /**
     * @Route("user/{id}/tasks", name="task_list")
     * 
     * ListAction
     *
     * @param  TaskRepository $taskRepository TaskRepository
     * @param  User           $user           User
     * @return void
     */
    public function listAction(TaskRepository $taskRepository, User $user)
    {
        $tasks = $taskRepository->findByUser($user);

        return $this->render('task/list.html.twig',
        [
            'tasks' => $tasks
        ]);
    }
  
    /**
     * @Route("user/{id}/done", name="done_task_list")
     * 
     * DoneListAction
     *
     * @param  TaskRepository $taskRepository TaskRepository
     * @param  User           $user           User
     * @return void
     */
    public function doneListAction(TaskRepository $taskRepository, User $user)
    {
        $tasks = $taskRepository->doneTasksByUser($user);

        return $this->render('task/list.html.twig',
        [
            'tasks' => $tasks
        ]);
    }
    
    /**
     * @Route("/task/create", name="task_create")
     * 
     * CreateAction
     *
     * @param  Request $request Request
     * @return void
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list', ['id' => $task->getUser()->getId()]);
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }
   
    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * 
     * EditAction
     *
     * @param  Task    $task    Task
     * @param  Request $request Request
     * @return void
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list', ['id' => $task->getUser()->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
    
    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * 
     * ToggleTaskAction
     *
     * @param  Tast $task Task
     * @return void
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list', ['id' => $task->getUser()->getId()]);
    }
    
    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * 
     * DeleteTaskAction
     *
     * @param  Task $task
     * @return void
     */
    public function deleteTaskAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list', ['id' => $this->getUser()->getId()]);
    }
}
