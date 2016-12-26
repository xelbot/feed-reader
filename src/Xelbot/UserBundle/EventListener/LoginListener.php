<?php

namespace Xelbot\UserBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Xelbot\UserBundle\Entity\User;

class LoginListener implements EventSubscriberInterface
{
    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User) {
            $user->setLastLogin(new \DateTime());

            $this->em->persist($user);
            $this->em->flush();
        }
    }
}
