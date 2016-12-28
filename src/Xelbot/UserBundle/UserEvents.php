<?php

namespace Xelbot\UserBundle;

/**
 * Contains all events thrown in the UserBundle.
 */
final class UserEvents
{
    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * @Event("Xelbot\UserBundle\Event\FormEvent")
     */
    const REGISTRATION_SUCCESS = 'xelbot.user.registration.success';
}
