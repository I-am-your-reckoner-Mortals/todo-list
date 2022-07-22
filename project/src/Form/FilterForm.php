<?php


namespace App\Form;


use App\Entity\Task;
use App\Models\Ordering;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class FilterForm extends AbstractType
{
    private TaskRepository $taskRepository;
    private UserRepository $userRepository;
    private WorkflowInterface $taskStateMachine;

    public function __construct(TaskRepository $taskRepository, UserRepository $userRepository, WorkflowInterface $taskStateMachine)
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->taskStateMachine = $taskStateMachine;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'required' => false,
            'empty_data' => null,
        ]);
        $builder->add('createdBy', ChoiceType::class, [
            'choices' => $this->userRepository->getUsers(),
            'required' => false,
            'empty_data' => null,
        ]);
        $builder->add('status', ChoiceType::class, [
            'choices' => $this->taskStateMachine->getDefinition()->getPlaces(),
            'required' => false,
            'empty_data' => null,
        ]);
        $builder->add(
            $builder->create('priority', FormType::class)
                ->add('priority_start', ChoiceType::class, [
                    'choices' => array_flip(Task::PRIORITIES),
                    'required' => true,
                ])
                ->add('priority_end', ChoiceType::class, [
                    'choices' => array_reverse(array_flip(Task::PRIORITIES)),
                    'required' => true,
                ])
        );
        $builder->add(
            $builder->create('orderBy', FormType::class)
                ->add('order_field', ChoiceType::class, [
                    'choices' => [
                        'status' => 'status',
                        'priority' => 'priority',
                        'createdAt' => 'priority',
                    ],
                    'required' => false,
                    'empty_data' => null,
                ])
                ->add('order_type', ChoiceType::class, [
                    'choices' => [
                        'asc' => 'ASC',
                        'desc' => 'DESC',
                    ],
                ])
        );
        $builder->add('apply', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-3'
            ]
        ]);
    }
}