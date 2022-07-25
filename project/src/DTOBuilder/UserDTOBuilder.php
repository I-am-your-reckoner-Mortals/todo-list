<?php


namespace App\DTOBuilder;


use App\DTO\UserDTO;
use App\Entity\User;

class UserDTOBuilder
{
    public function build(User $user): UserDTO
    {

        $dto = new UserDTO();

        $dto->id = $user->getId();
        $dto->name = $user->getName();
        $dto->surname = $user->getSurname();
        $dto->email = $user->getEmail();

        return $dto;
    }
}