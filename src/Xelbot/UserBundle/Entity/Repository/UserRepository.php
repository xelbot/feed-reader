<?php

namespace Xelbot\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Xelbot\UserBundle\Entity\User;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
