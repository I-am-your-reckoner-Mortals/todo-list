<?php


namespace App\DTO;


class UserDTO implements DTOInterface
{
    public ?int $id;
    public string $name;
    public string $surname;
    public ?string $email;
}