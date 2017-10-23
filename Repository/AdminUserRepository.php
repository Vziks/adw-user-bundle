<?php

namespace ADW\UserBundle\Repository;

use ADW\UserBundle\Entity\AdminUser;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class AdminUserRepository
 *
 * @package AppBundle\Repository
 * @author Anton Prokhorov
 */
class AdminUserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * @var LoggerInterface
     */
    protected  $logger;


    /**
     * @param $email
     * @return null|object
     */
    public function findOneByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        return $this->findOneByEmail($username);
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return $class == 'ADW\UserBundle\Entity\AdminUser';
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}