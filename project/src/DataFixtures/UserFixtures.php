<?php


namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\PasswordHasherEncoder;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
        $userData = [
            [
                'name' => "super_admin",
                'role' => "ROLE_SUPER_ADMIN"
            ],
            [
                'name' => "admin",
                'role' => "ADMIN"
            ],
            [
                'name' => "manager",
                'role' => "MANAGER"
            ],
            [
                'name' => "user",
                'role' => "USER"
            ],
        ];

        $roles = [];

        foreach ($userData as $data) {
            $role = new Role();
            $role->setName($data['name']);
            $role->setRole($data['role']);

            $roles[] = $role;

            $manager->persist($role);
        }
        $manager->flush();

        foreach ($userData as $key => $data) {
            $user = new User();
            $user->setEmail($data['name'] . '@test.com');
            $user->setName($data['name']);
            $user->setSurname($data['name']);
            $user->setPassword($this->userPasswordHasherInterface->hashPassword($user,'123456'));
            $user->setRoles([$roles[$key]]);



            $manager->persist($user);
        }
        $manager->flush();

    }
}