<?php


namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $roles = [
            [
                'name' => "super_admin",
                'role' => "SUPER_ADMIN"
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

        foreach ($roles as $data) {
            $role = new Role();
            $role->setName($data['name']);
            $role->setRole($data['role']);

            $manager->persist($role);
        }
        $manager->flush();
    }
}