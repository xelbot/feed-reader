<?php

namespace Xelbot\UserBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Xelbot\AppBundle\Mailer\Mailer;
use Xelbot\UserBundle\Event\FormEvent;
use Xelbot\UserBundle\UserEvents;

class EmailConfirmationListener implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * EmailConfirmationListener constructor.
     *
     * @param Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param SessionInterface $session
     */
    public function __construct(Mailer $mailer, UrlGeneratorInterface $router, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event): void
    {
        /** @var $user \Xelbot\UserBundle\Entity\User */
        $user = $event->getForm()->getData();

        if ($user->getConfirmationToken() === null) {
            $user->setConfirmationToken($this->generateToken());
        }

        $this->mailer->sendConfirmationEmailMessage($user);
    }

    /**
     * @return string
     */
    private function generateToken(): string
    {
        return strtr(base64_encode(random_bytes(30)), '+/', '-_');
    }
}
