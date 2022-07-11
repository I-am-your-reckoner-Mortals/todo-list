<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        $tasks = $this->taskRepository->findAll();

        return $this->render('home.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
