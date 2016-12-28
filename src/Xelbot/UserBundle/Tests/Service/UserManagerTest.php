<?php

namespace Xelbot\UserBundle\Tests\Service;

use Xelbot\UserBundle\Entity\User;
use Xelbot\UserBundle\Service\UserManager;

class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $om;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $encoderFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repository;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->om = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->getMock();
        $this->encoderFactory = $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface')->getMock();
        $this->repository = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')->getMock();

        $class = $this->getMockBuilder('Doctrine\Common\Persistence\Mapping\ClassMetadata')->getMock();

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo('UserBundle:User'))
            ->will($this->returnValue($this->repository));

        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo('UserBundle:User'))
            ->will($this->returnValue($class));

        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('UserBundle:User'));

        $this->userManager = new UserManager($this->om, $this->encoderFactory);
    }

    public function testDeleteUser()
    {
        $user = $this->getUser();
        $this->om->expects($this->once())->method('remove')->with($this->equalTo($user));
        $this->om->expects($this->once())->method('flush');

        $this->userManager->deleteUser($user);
    }

    public function testFindUserBy()
    {
        $criteria = ['username' => 'tester'];
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo($criteria))
            ->will($this->returnValue(null));

        $this->userManager->findUserBy($criteria);
    }

    /**
     * @return mixed
     */
    protected function getUser()
    {
        $userClass = User::class;

        return new $userClass();
    }
}
