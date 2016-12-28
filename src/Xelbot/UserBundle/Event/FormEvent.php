<?php

namespace Xelbot\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormEvent extends Event
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * FormEvent constructor.
     *
     * @param FormInterface $form
     * @param Request $request
     */
    public function __construct(FormInterface $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
