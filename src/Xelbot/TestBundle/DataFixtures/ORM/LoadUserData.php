<?php

namespace Xelbot\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var int
     */
    protected $countOfGeneratedUsers = 20;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('xelbot.user.user_manager');
        $userManager->createUser('admin@example.org', 'admin', 'test', true);

        $faker = Factory::create();
        $faker->seed(1234);

        for ($i = 0; $i < $this->countOfGeneratedUsers; $i++) {
            $userManager->createUser(
                $faker->email,
                $faker->userName,
                'test',
                $faker->numberBetween(0, 100) < 75
            );
        }
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
