<?php


namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO implements DTOInterface
{
    /**
     * @Assert\Type("int")
    */
    public ?int $id;

    /**
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage = "Task title must be longer than {{ min }} characters long",
     *     maxMessage = "Task title must be shorter than {{ max }} characters long"
     * )
     */
    public string $name;
    /**
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage = "Task title must be longer than {{ min }} characters long",
     *     maxMessage = "Task title must be shorter than {{ max }} characters long"
     * )
     */
    public string $surname;

    /**
     * @Assert\Email()
    */
    public ?string $email;
}