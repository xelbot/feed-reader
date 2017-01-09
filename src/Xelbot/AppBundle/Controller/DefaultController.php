<?php

namespace Xelbot\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Xelbot\AppBundle\Form\ContactFormType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/contact", name="contact")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('xelbot.app.mailer')->sendContactUsEmail($form->getData());

            $this->addFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');

            return $this->redirectToRoute('contact');
        }

        return $this->render('AppBundle:Default:contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
