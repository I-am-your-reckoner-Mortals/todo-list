<?php


namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private RoleRepository $roleRepository;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, RoleRepository $roleRepository)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->roleRepository = $roleRepository;
    }

    public function load(ObjectManager $manager)
    {
        $roles = $this->roleRepository->findAll();

        foreach ($roles as $role) {
            $user = new User();
            $user->setEmail($role->getName() . '@test.com');
            $user->setName($role->getName());
            $user->setSurname($role->getName());
            $user->setPassword($this->userPasswordHasherInterface->hashPassword($user,'123456'));
            $user->setRoles([$role]);

            $manager->persist($user);
        }
        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
          RoleFixture::class
        ];
    }
}