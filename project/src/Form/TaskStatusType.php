<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Workflow\WorkflowInterface;

class TaskStatusType extends AbstractType
{
    private WorkflowInterface $taskStateMachine;

    public function __construct(WorkflowInterface $taskStateMachine)
    {
        $this->taskStateMachine = $taskStateMachine;
    }


    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'choices' => $statuses
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}