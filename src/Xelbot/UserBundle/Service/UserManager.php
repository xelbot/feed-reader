<?php

namespace Xelbot\UserBundle\Service;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Xelbot\AppBundle\Service\BaseManager;
use Xelbot\UserBundle\Entity\User;

class UserManager extends BaseManager
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function setEncoderFactory(EncoderFactoryInterface $encoderFactory): void
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Creates an empty user instance.
     *
     * @return User
     */
    public function createUser(): User
    {
        $user = new User();

        return $user;
    }

    /**
     * Updates a user.
     *
     * @param User $user
     * @param bool $andFlush
     */
    public function updateUser(User $user, $andFlush = true): void
    {
        $this->updatePassword($user);

        $this->save($user, $andFlush);
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param User $user
     */
    public function updatePassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();

        if (strlen($plainPassword) === 0) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return array
     */
    public function findUsers(): array
    {
        return $this->em->getRepository('UserBundle:User')->findAll();
    }

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return User|null
     */
    public function findUserByUserName($username): ?User
    {
        return $this->findUserBy(['username' => $username]);
    }

    /**
     * Finds a user by its confirmationToken.
     *
     * @param string $token
     *
     * @return User|null
     */
    public function findUserByConfirmationToken($token): ?User
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return User|null
     */
    public function findUserBy(array $criteria): ?User
    {
        return $this->em->getRepository('UserBundle:User')->findOneBy($criteria);
    }
}
