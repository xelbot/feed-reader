<?php

namespace Xelbot\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class RoleCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('xelbot:user:role')
            ->setDescription('Assigning a role to a user.')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('role', InputArgument::REQUIRED, 'The role'),
                new InputOption('delete', 'd', InputOption::VALUE_NONE, 'This option allows to delete the role from the user.'),
            ])
            ->setHelp('This command allows you to assign roles to the users.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');
        $deleteRole = $input->getOption('delete');

        $userManager = $this->getContainer()->get('xelbot.user.user_manager');
        $user = $userManager->findUserByUsernameOrThrowException($username);

        if (!$deleteRole) {
            $userManager->assignRole($user, $role);
            $output->writeln(sprintf('The role <comment>%s</comment> has been successfully assigned to the user.', $role));
        } else {
            $userManager->deleteRole($user, $role);
            $output->writeln(sprintf('The role <comment>%s</comment> has been successfully deleted from the user.', $role));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('role')) {
            $question = new Question('Please choose a role:');
            $question->setValidator(function ($role) {
                if (empty($role)) {
                    throw new \Exception('Role can not be empty');
                }

                return $role;
            });
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
