<?php

namespace Xelbot\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Xelbot\UserBundle\Event\FormEvent;
use Xelbot\UserBundle\Form\RegistrationFormType;
use Xelbot\UserBundle\UserEvents;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="registration")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function registerAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $userManager = $this->get('xelbot.user.user_manager');
        $dispatcher = $this->get('event_dispatcher');
        $user = $userManager->createUser();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(UserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            $this->addFlash('success', 'We\'ve sent a verification link to your email address. Please check your inbox and click the link to log in.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('UserBundle:Registration:register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm/{token}", name="registration_confirm")
     *
     * @param string $token
     *
     * @return RedirectResponse
     */
    public function confirmAction($token)
    {
        $userManager = $this->get('xelbot.user.user_manager');
        $user = $userManager->findUserByConfirmationToken($token);

        if (empty($user)) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $userManager->updateUser($user);

        return $this->redirectToRoute('homepage');
    }
}
