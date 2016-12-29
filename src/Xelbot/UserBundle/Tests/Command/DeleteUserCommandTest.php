<?php

namespace Xelbot\UserBundle\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xelbot\UserBundle\Command\DeleteUserCommand;
use Xelbot\UserBundle\Entity\User;

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
        $this->assertRegExp('/User user has been deleted./', $commandTester->getDisplay());
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

        $commandTester = $this->createCommandTester($this->getContainer('user'), $application);
        $exitCode = $commandTester->execute([], [
            'decorated' => false,
            'interactive' => true,
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/User user has been deleted./', $commandTester->getDisplay());
    }

    /**
     * @param ContainerInterface $container
     * @param Application|null $application
     *
     * @return CommandTester
     */
    private function createCommandTester(ContainerInterface $container, Application $application = null)
    {
        if ($application === null) {
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
        $container = $this
            ->getMockBuilder('Symfony\Component\DependencyInjection\ContainerInterface')
            ->getMock();

        $userManager = $this->getMockBuilder('Xelbot\UserBundle\Service\UserManager')
            ->disableOriginalConstructor()
            ->getMock();

        $user = $this->createMock(User::class);

        $userManager->expects($this->once())
            ->method('findUserByUsernameOrThrowException')
            ->with($username)
            ->will($this->returnValue($user));

        $container
            ->expects($this->once())
            ->method('get')
            ->with('xelbot.user.user_manager')
            ->will($this->returnValue($userManager));

        return $container;
    }
}
