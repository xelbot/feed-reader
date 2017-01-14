<?php

namespace Xelbot\AppBundle\Tests\Form;

use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Xelbot\AppBundle\Form\ContactFormType;

class ContactFormTypeTest extends TypeTestCase
{
    private $validator;

    /**
     * @inheritdoc
     */
    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));

        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata('Symfony\Component\Form\Form')));

        return [new ValidatorExtension($this->validator)];
    }

    public function testSubmit()
    {
        $form = $this->factory->create(ContactFormType::class);
        $formData = [
            'name' => 'tester',
            'email' => 'tester@tester.com',
            'subject' => 'test contact us',
            'message' => 'test message',
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame('tester', $form->get('name')->getData());
        $this->assertSame('tester@tester.com', $form->get('email')->getData());
        $this->assertSame('test contact us', $form->get('subject')->getData());
        $this->assertSame('test message', $form->get('message')->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
