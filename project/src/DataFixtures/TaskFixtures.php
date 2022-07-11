<?php


namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\PasswordHasherEncoder;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName('BAD_PM');
        $role->setRole('BAD_PM');

        $manager->persist($role);
        $manager->flush();

        $user = new User();
        $user->setName('BAD');
        $user->setSurname('PM');
        $user->setEmail('bad_pm@bad.pm.schoool.org');
        $user->setPassword(hash('md5', 'password'));
        $user->setRoles([$role]);

        $manager->persist($user);
        $manager->flush();

        $tasksData = [
            [
                'title' => "Сделать ту штуку про которую мы говорили 3 недели назад ",
                'priority' => 1,
                'description' => "Ну ты же понишь что там надо сделать",
            ],
            [
                'title' => "Сделать прикольную фитчу (не баг)",
                'priority' => 2,
                'description' => "Зделать прикольную фитчу",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => 1,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => 1,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => 1,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => 1,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Починить сервер",
                'priority' => 1,
                'description' => "Починить сервер. Не переходит на google.com",
            ],
            [
                'title' => "Сделать так чтобы не лагало",
                'priority' => 1,
                'description' => "Убрать лаги на моём Intel 4004",
            ],
            [
                'title' => "Сдвинуть кнопку на 3 пикселя в право",
                'priority' => 1,
                'description' => "Сдвинуть кнопку на 3 пикселя в право",
            ],
            [
                'title' => "Вернуть кнопку обратно",
                'priority' => 1,
                'description' => "Вернуть кнопку обратно на 3 пикселя в лево",
            ],
            [
                'title' => "Обьясни фронту как пользоватся документацией",
                'priority' => 1,
                'description' => "Обьясни фронту как пользоватся документацией",
            ],
            [
                'title' => "🔧  🐢  🖼  🔛  📱",
                'priority' => 1,
                'description' => "🔧  🐢  🖼  🔛  📱",
            ],
        ];


        foreach ($tasksData as $taskData) {
            $task = new Task();
            $task->setTitle($taskData['title']);
            $task->setStatus('analyze');
            $task->setPriority($taskData['priority']);
            $task->setDescription($taskData['description']);
            $task->setCreatedBy($user);

            $manager->persist($task);
        }

        $manager->flush();

    }
}