<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\TaskService;

class TaskController extends AbstractController
{
    private TaskService $taskService;
    private TaskRepository $taskRepository;

    public function __construct(
        TaskService $taskService,
        TaskRepository $taskRepository
    ) {
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
    }


    /**
     * @Route("/task/create", name="task_create")
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(TaskFormType::class, new Task());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->taskService->create(
                $form->getData(),
                $this->getUser()
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('task/task__create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/task/{id}/delete", name="task_delete", requirements={"id"="\d+"})
     */
    public function delete(): Response
    {
        return $this->render('task/task__create.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * @Route("/task/{id}", name="task_show", requirements={"id"="\d+"})
     */
    public function showTask(int $id): Response
    {
        $task = $this->taskRepository->find(['id' => $id]);
        $form = $this->createForm(TaskFormType::class, $task);

        return $this->render('task/task__view.html.twig', [
            'task' => $form->createView()
        ]);
    }

    /**
     * @Route("/task/{id}/edit", name="task_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, int $id): Response
    {
        $task = $this->taskRepository->find(['id' => $id]);
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->taskService->create(
                $form->getData(),
                $this->getUser()
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('task/task__create.html.twig', [
            'form' => $form,
        ]);
    }


}
