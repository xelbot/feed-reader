<?php

namespace Xelbot\UserBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Xelbot\UserBundle\Entity\Repository\UserRepository;
use Xelbot\UserBundle\Entity\User;

class UserManager
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * UserManager Constructor.
     *
     * @param ObjectManager $om
     * @param EncoderFactoryInterface $encoderFactory
     *
     * @internal param PasswordUpdater $passwordUpdater
     */
    public function __construct(ObjectManager $om, EncoderFactoryInterface $encoderFactory)
    {
        $this->objectManager = $om;
        $this->encoderFactory = $encoderFactory;
        $this->repository = $om->getRepository('UserBundle:User');
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
     * Deletes a user.
     *
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return array
     */
    public function findUsers(): array
    {
        return $this->repository->findAll();
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
        return $this->repository->findOneBy($criteria);
    }
}
