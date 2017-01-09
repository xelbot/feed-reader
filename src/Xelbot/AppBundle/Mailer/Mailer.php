<?php

namespace Xelbot\AppBundle\Mailer;

use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Xelbot\UserBundle\Entity\User;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param EngineInterface $templating
     * @param array $parameters
     */
    public function __construct($mailer, UrlGeneratorInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->parameters = $parameters;
    }

    /**
     * Send an email to a user to confirm the account creation.
     *
     * @param User $user
     */
    public function sendConfirmationEmailMessage(User $user)
    {
        $template = 'UserBundle:Registration:email.txt.twig';
        $subject = 'Account Activation';
        $url = $this->router->generate(
            'registration_confirm',
            [
                'token' => $user->getConfirmationToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $body = $this->templating->render($template, [
            'user' => $user,
            'confirmationUrl' => $url,
        ]);

        $this->sendEmailMessage($body, $subject, $user->getEmail());
    }

    /**
     * Send contact us email
     *
     * @param $params
     */
    public function sendContactUsEmail($params)
    {
        $template = 'AppBundle:emails:contact.txt.twig';
        $body = $this->templating->render($template, $params);
        $subject = 'Feed Reader | Contact Us';

        $this->sendEmailMessage($body, $subject, $this->parameters['admin_emails']);
    }

    /**
     * @param string $body
     * @param $subject
     * @param string $toEmail
     */
    protected function sendEmailMessage($body, $subject, $toEmail)
    {
        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom([$this->parameters['from_email'] => $this->parameters['from_name']])
            ->setTo($toEmail)
            ->setBody($body);

        $this->mailer->send($message);
    }
}
