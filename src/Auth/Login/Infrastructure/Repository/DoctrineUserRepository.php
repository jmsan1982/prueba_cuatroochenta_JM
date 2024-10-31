<?php

namespace App\Auth\Login\Infrastructure\Repository;

use App\Auth\Login\Domain\User;
use App\Auth\Login\Domain\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user):void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->getEntityManager()->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);
    }
}
