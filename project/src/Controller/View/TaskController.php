<?php

namespace App\Controller\View;

use App\Entity\Task;
use App\Form\FilterForm;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use App\Services\FilterService;
use ChildTaskIsNotComplete;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\TaskService;

class TaskController extends AbstractFOSRestController
{
    private TaskService $taskService;
    private TaskRepository $taskRepository;
    private FilterService $filterService;

    public function __construct(
        TaskService $taskService,
        TaskRepository $taskRepository,
        FilterService $filterService
    ) {
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
        $this->filterService = $filterService;
    }

    /**
     * @Route("/task", name="app_home")
     */
    public function index(ParamFetcher $paramFetcher, Request $request): Response
    {
        $filterForm = $this->createForm(FilterForm::class);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $tasks = $this->filterService->search(
                $this->taskRepository,
                $filterForm->getData(),
                [$paramFetcher->get('orderBy')]
            );

            return $this->render('home.html.twig', [
                'filter' => $filterForm->createView(),
                'tasks' => $tasks,
            ]);
        }

        $tasks = $this->filterService->search(
            $this->taskRepository
        );

        return $this->render('home.html.twig', [
            'filter' => $filterForm->createView(),
            'tasks' => $tasks,
        ]);
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

        return $this->renderForm('task/task__action.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/task/{id}/create_child", name="task_create_child")
     */
    public function createChild(Request $request, int $id): Response
    {
        $form = $this->createForm(TaskFormType::class, new Task());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->create(
                $form->getData(),
                $this->getUser(),
                $this->taskRepository->find(['id' => $id])
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('task/task__action.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/task/{id}/delete", name="task_delete", requirements={"id"="\d+"})
     */
    public function delete(Request $request, int $id): Response
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);

        try {
            $this->taskService->delete($task);
        } catch (Exception $e) {
            return $this->render('task/task__action.html.twig', [
                'error' => $e->getMessage(),
                'controller_name' => 'TaskController',
            ]);
        }

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/task/{id}", name="task_show", requirements={"id"="\d+"})
     */
    public function showTask(int $id): Response
    {
        $task = $this->taskRepository->find(['id' => $id]);
        $form = $this->createForm(TaskFormType::class, $task);

        return $this->render('task/task__view.html.twig', [
            'form' => $form->createView()
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

            try {
                $this->taskService->update($task);
            } catch (ChildTaskIsNotComplete $e) {
                return $this->renderForm('task/task__action.html.twig', [
                    'error' => $e->getMessage(),
                    'form' => $form,
                ]);
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('task/task__action.html.twig', [
            'form' => $form,
        ]);
    }


}
