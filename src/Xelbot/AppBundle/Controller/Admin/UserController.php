<?php

namespace Xelbot\AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Xelbot\UserBundle\Entity\User;

/**
 * User controller.
 *
 * @Route("admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="admin_user_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        return ['users' => $users];
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $userManager = $this->get('xelbot.user.user_manager');
        $user = $userManager->create();
        $form = $this->createForm('Xelbot\AppBundle\Form\UserType', $user, [
            'validation_groups' => ['create', 'Default'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);

            $this->addFlash('success', 'User has been created.');

            return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="admin_user_show")
     * @Method("GET")
     *
     * @param User $user
     *
     * @return array
     * @Template()
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return [
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request $request
     * @param User $user
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('Xelbot\AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'User has been saved.');

            return $this->redirectToRoute('admin_user_index', ['id' => $user->getId()]);
        }

        return [
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, User $user)
    {
        $userManager = $this->get('xelbot.user.user_manager');
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->delete($user);

            $this->addFlash('success', 'User has been deleted.');
        }

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
