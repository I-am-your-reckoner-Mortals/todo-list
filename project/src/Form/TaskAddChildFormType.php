<?php

namespace App\Form;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TaskAddChildFormType extends AbstractType
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('childTask', ChoiceType::class, [
            'choices' => $this->mapChildTasks($options['data']->getId())
        ]);
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
