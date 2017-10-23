<?php

namespace ADW\UserBundle\DataFixtures\ORM;

use ADW\UserBundle\Entity\AdminUser;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAdminData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = static::getFakeAdmin();

        $manager->persist($admin);
        $manager->flush();
    }

    static public function getFakeAdmin()
    {
        $admin = new AdminUser();
        $admin->setEmail("admin@gmail.com");
        $admin->setPlainPassword("admin");

        return $admin;
    }


}