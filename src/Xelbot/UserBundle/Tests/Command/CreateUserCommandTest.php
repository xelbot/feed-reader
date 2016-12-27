<?php

namespace Xelbot\UserBundle\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xelbot\UserBundle\Command\CreateUserCommand;

class CreateUserCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getContainer('user@example.org', 'user', 'password', true));
        $exitCode = $commandTester->execute([
            'username' => 'user',
            'email' => 'user@example.org',
            'password' => 'password',
        ], [
            'decorated' => false,
            'interactive' => false,
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Created user user/', $commandTester->getDisplay());
    }

    public function testExecuteInteractiveWithQuestionHelper()
    {
        $application = new Application();

        $helper = $this->getMockBuilder('Symfony\Component\Console\Helper\QuestionHelper')
            ->setMethods(['ask'])
            ->getMock();

        $helper->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue('user'));

        $helper->expects($this->at(1))
            ->method('ask')
            ->will($this->returnValue('email'));

        $helper->expects($this->at(2))
            ->method('ask')
            ->will($this->returnValue('pass'));

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester(
            $this->getContainer('email', 'user', 'pass', true, false), $application
        );
        $exitCode = $commandTester->execute([], [
            'decorated' => false,
            'interactive' => true,
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Created user user/', $commandTester->getDisplay());
    }

    /**
     * @param ContainerInterface $container
     * @param Application|null $application
     *
     * @return CommandTester
     */
    private function createCommandTester(ContainerInterface $container, Application $application = null)
    {
        if (null === $application) {
            $application = new Application();
        }

        $application->setAutoExit(false);

        $command = new CreateUserCommand();
        $command->setContainer($container);

        $application->add($command);

        return new CommandTester($application->find('xelbot:user:create'));
    }

    /**
     * @param $username
     * @param $password
     * @param $email
     * @param $active
     *
     * @return mixed
     */
    private function getContainer($email, $username, $password, $active)
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();

        $manipulator = $this->getMockBuilder('Xelbot\UserBundle\Util\UserManipulator')
            ->disableOriginalConstructor()
            ->getMock();

        $manipulator
            ->expects($this->once())
            ->method('create')
            ->with($email, $username, $password, $active);

        $container
            ->expects($this->once())
            ->method('get')
            ->with('xelbot.user.util.user_manipulator')
            ->will($this->returnValue($manipulator));

        return $container;
    }
}
