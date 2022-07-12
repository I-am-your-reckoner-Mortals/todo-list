<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

//    /**
//     * @return Task[]
//     */
//    public function findTaskByTitleOrId(string $name): array
//    {
//        $qb = $this->createQueryBuilder('t');
//        $qb
//            ->where($qb->expr()->like('t.title',':likeName'))
//            ->orWhere($qb->expr()->like('t.id', ':likeName'))
//            ->setParameter(':likeName', '%'.$name.'%' )
//            ->setMaxResults(10);
//        return $qb->getQuery()->getResult();
//    }

    /**
     * @return Task[]
    */
    public function findPossibleChildTasks(int $id): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->where($qb->expr()->neq('t.id',':id'))
            ->setParameter(':id', $id );
        return $qb->getQuery()->getResult();
    }
}
