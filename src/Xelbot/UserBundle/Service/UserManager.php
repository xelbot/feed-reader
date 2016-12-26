<?php

namespace Xelbot\UserBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Xelbot\UserBundle\Entity\Repository\UserRepository;
use Xelbot\UserBundle\Entity\User;
use Xelbot\UserBundle\Util\PasswordUpdater;

class UserManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var PasswordUpdater
     */
    protected $passwordUpdater;

    /**
     * UserManager Constructor.
     *
     * @param PasswordUpdater $passwordUpdater
     * @param ObjectManager $om
     */
    public function __construct(PasswordUpdater $passwordUpdater, ObjectManager $om)
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->objectManager = $om;
        $this->repository = $om->getRepository('UserBundle:User');

        $metadata = $om->getClassMetadata('UserBundle:User');
        $this->class = $metadata->getName();
    }

    /**
     * Creates an empty user instance.
     *
     * @return User
     */
    public function createUser()
    {
        $user = new $this->class();

        return $user;
    }

    /**
     * Updates a user.
     *
     * @param User $user
     * @param bool $andFlush
     */
    public function updateUser(User $user, $andFlush = true)
    {
        $this->updatePassword($user);

        $this->objectManager->persist($user);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param User $user
     */
    public function updatePassword(User $user)
    {
        $this->passwordUpdater->hashPassword($user);
    }

    /**
     * Deletes a user.
     *
     * @param User $user
     */
    public function deleteUser(User $user)
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return \Traversable
     */
    public function findUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return User or null if user does not exist
     */
    public function findUserByUserName($username)
    {
        return $this->repository->findUserByUserName($username);
    }
}
