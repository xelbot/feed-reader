<?php

namespace Xelbot\UserBundle\Util;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Xelbot\UserBundle\Entity\User;

/**
 * Class updating the hashed password in the user when there is a new password.
 */
class PasswordUpdater
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * PasswordUpdater constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Updates the hashed password in the user when there is a new password.
     *
     * @param User $user
     */
    public function hashPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
