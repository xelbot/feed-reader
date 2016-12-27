<?php

namespace Xelbot\UserBundle\Util;

use Xelbot\UserBundle\Entity\User;
use Xelbot\UserBundle\Service\UserManager;

class UserManipulator
{
    /**
     * User manager.
     *
     * @var UserManager
     */
    private $userManager;

    /**
     * UserManipulator constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Creates a user and returns it.
     *
     * @param string $email
     * @param string $username
     * @param string $password
     * @param $active
     *
     * @return \Xelbot\UserBundle\Entity\User
     */
    public function create($email, $username, $password, $active)
    {
        $user = $this->userManager->createUser();
        $user->setEmail($email)
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setEnabled($active);
        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * Deletes a user by username.
     *
     * @param $username
     */
    public function delete($username)
    {
        $user = $this->findUserByUsernameOrThrowException($username);
        $this->userManager->deleteUser($user);
    }

    /**
     * Finds a user by his username and throws an exception if we can't find it.
     *
     * @param string $username
     *
     * @throws \InvalidArgumentException When user does not exist
     *
     * @return User
     */
    private function findUserByUsernameOrThrowException($username)
    {
        $user = $this->userManager->findUserByUsername($username);

        if (!$user) {
            throw new \InvalidArgumentException(sprintf('User identified by "%s" username does not exist.', $username));
        }

        return $user;
    }
}
