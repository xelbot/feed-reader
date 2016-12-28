<?php

namespace Xelbot\UserBundle\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xelbot\UserBundle\Command\DeleteUserCommand;

class DeleteUserCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getContainer('user'));
        $exitCode = $commandTester->execute([
            'username' => 'user',
        ], [
            'decorated' => false,
            'interactive' => false,
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/User has been deleted./', $commandTester->getDisplay());
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

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester(
            $this->getContainer('user'), $application
        );
        $exitCode = $commandTester->execute([], [
            'decorated' => false,
            'interactive' => true,
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/User has been deleted./', $commandTester->getDisplay());
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

        $command = new DeleteUserCommand();
        $command->setContainer($container);

        $application->add($command);

        return new CommandTester($application->find('xelbot:user:delete'));
    }

    /**
     * @param $username
     *
     * @return mixed
     */
    private function getContainer($username)
    {
        $container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')->getMock();

        $manipulator = $this->getMockBuilder('Xelbot\UserBundle\Util\UserManipulator')
            ->disableOriginalConstructor()
            ->getMock();

        $manipulator
            ->expects($this->once())
            ->method('delete')
            ->with($username);

        $container
            ->expects($this->once())
            ->method('get')
            ->with('xelbot.user.util.user_manipulator')
            ->will($this->returnValue($manipulator));

        return $container;
    }
}
