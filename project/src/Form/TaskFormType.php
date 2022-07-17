<?php

namespace App\Form;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Services\TaskService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{
    private TaskService $taskService;
    private TaskRepository $taskRepository;

    public function __construct(TaskService $taskService, TaskRepository $taskRepository)
    {
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**@var Task $task */
        $task = $options['data'];
        if ($task->getParentTask()) {
            $builder->add('parentTask', ChoiceType::class,[
                'choices' => $this->mapChildTasks($task->getId()),
            ]);
        }

        $builder
            ->add('title', TextType::class)
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' => $this->taskService->getAllowedStatuses($task)
                ]
            )
            ->add('priority',
                ChoiceType::class,
                [
                    'choices' => array_flip(Task::PRIORITIES)
                ])
            ->add('description');


    }

    public function mapChildTasks(int $id): array
    {
        $mappedRes = [];
        foreach ($this->taskRepository->findPossibleChildTasks($id) as $task) {
            $mappedRes[$task->getId() . ':' . $task->getTitle()] = $task;
        }

        return $mappedRes;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
