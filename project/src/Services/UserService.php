<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\UserRolesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserService
{
    private const USER_DEFAULT = 'ROLE_USER';

    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    private UserPasswordHasherInterface $userPasswordHasherInterface;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;

        $this->entityManager = $entityManager;
    }


    public function create(\RegisterDTO $registerDTO, string $password, array $roles = null): User
    {
        if (null !== $this->userRepository->findOneBy(['email' => $registerDTO->email])) {
            throw new Exception('This email is already taken');
        }

        $user = new User();
        $user->setEmail($registerDTO->email);
        $user->setName($registerDTO->name);
        $user->setSurname($registerDTO->surname);
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $password));

        if (!$roles) {
            $defaultRole = $this->roleRepository->findBy(['role' => self::USER_DEFAULT]);
            $user->setRoles($defaultRole);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}