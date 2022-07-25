<?php


namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use App\Models\TaskPriorities;
use App\Models\TaskStatuses;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName('BAD_PM');
        $role->setRole('BAD_PM');

        $manager->persist($role);
        $manager->flush();

        $user = new User();
        $user->setName('bad');
        $user->setSurname('pm');
        $user->setEmail('bad_pm@BadPmSchoool.org');
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user,'123456'));
        $user->setRoles([$role]);

        $manager->persist($user);
        $manager->flush();

        $tasksData = [
            [
                'title' => "Сделать ту штуку про которую мы говорили 3 недели назад ",
                'priority' => TaskPriorities::VERY_LOW,
                'status' => TaskStatuses::ANALYZE,
                'description' => "Ну ты же понишь что там надо сделать",
            ],
            [
                'title' => "Сделать прикольную фитчу (не баг)",
                'priority' => TaskPriorities::LOW,
                'status' => TaskStatuses::TODO,
                'description' => "Зделать прикольную фитчу",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => TaskPriorities::LOW,
                'status' => TaskStatuses::ANALYZE,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => TaskPriorities::LOW,
                'status' => TaskStatuses::ANALYZE,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => TaskPriorities::LOW,
                'status' => TaskStatuses::ANALYZE,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Пофиксить баг",
                'priority' => TaskPriorities::VERY_HEIGHT,
                'status' => TaskStatuses::ANALYZE,
                'description' => "Пофиксить баг",
            ],
            [
                'title' => "Починить сервер",
                'priority' => TaskPriorities::VERY_HEIGHT,
                'status' => TaskStatuses::TODO,
                'description' => "Починить сервер. Не переходит на google.com",
            ],
            [
                'title' => "Сделать так чтобы не лагало",
                'priority' => TaskPriorities::VERY_HEIGHT,
                'status' => TaskStatuses::TODO,
                'description' => "Убрать лаги на моём Intel 4004",
            ],
            [
                'title' => "Сдвинуть кнопку на 3 пикселя в право",
                'priority' => TaskPriorities::VERY_LOW,
                'status' => TaskStatuses::TODO,
                'description' => "Сдвинуть кнопку на 3 пикселя в право",
            ],
            [
                'title' => "Вернуть кнопку обратно",
                'priority' => TaskPriorities::VERY_LOW,
                'status' => TaskStatuses::TODO,
                'description' => "Вернуть кнопку обратно на 3 пикселя в лево",
            ],
            [
                'title' => "Обьясни фронту как пользоватся документацией",
                'priority' => TaskPriorities::VERY_LOW,
                'status' => TaskStatuses::TODO,
                'description' => "Обьясни фронту как пользоватся документацией",
            ],
            [
                'title' => "🔧  🐢  🖼  🔛  📱",
                'priority' => TaskPriorities::VERY_LOW,
                'status' => TaskStatuses::TODO,
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

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}