<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    /**
     * __construct
     *
     * @param  ManagerRegistry $registry Registry
     * @return void
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * FindllOrderedByDate
     *
     * @return void
     */
    public function findAllOrderedByDate()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * FindByUser
     *
     * @param  User $user User
     * @return void
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.createdBy = :user')
            ->setParameter('user', $user)
            ->andWhere('t.isDone = 0')
            ->getQuery()
            ->getResult();
    }

    /**
     * doneTasksByUser
     *
     * @param  User $user User
     * @return void
     */
    public function doneTasksByUser(User $user)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.createdBy = :user')
            ->setParameter('user', $user)
            ->andWhere('t.isDone = 1')
            ->getQuery()
            ->getResult();
    }
}
