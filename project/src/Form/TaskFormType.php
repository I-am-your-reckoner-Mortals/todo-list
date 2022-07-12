<?php

namespace App\Form;

use App\Entity\Task;
use App\Services\TaskService;
use App\Twig\TaskExtension;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Workflow\WorkflowInterface;
use function Sodium\add;


class TaskFormType extends AbstractType
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**@var Task $task */
        $task = $options['data'];
        if ($task->getChildTask()) {
            $builder->add('childTask', TextType::class, [
                'attr' => [
                    'disabled' => 'disabled'
                ]
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
