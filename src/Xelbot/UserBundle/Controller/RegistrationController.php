<?php

namespace Xelbot\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xelbot\UserBundle\Form\RegistrationFormType;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function registerAction(Request $request)
    {
        $userManager = $this->get('xelbot.user.service.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Registration:register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
