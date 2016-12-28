<?php

namespace Xelbot\UserBundle\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use Xelbot\UserBundle\Entity\User;
use Xelbot\UserBundle\Form\RegistrationFormType;

class RegistrationFormTypeTest extends TypeTestCase
{
    public function testSubmit()
    {
        $user = new User();

        $form = $this->factory->create(RegistrationFormType::class, $user);
        $formData = [
            'username' => 'tester',
            'email' => 'tester@tester.com',
            'plainPassword' => [
                'first' => 'test',
                'second' => 'test',
            ],
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($user, $form->getData());
        $this->assertSame('tester', $user->getUsername());
        $this->assertSame('tester@tester.com', $user->getEmail());
        $this->assertSame('test', $user->getPlainPassword());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
