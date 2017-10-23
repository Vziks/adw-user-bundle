<?php


namespace ADW\UserBundle\EventListener;

use ADW\UserBundle\Entity\AdminUser;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;


/**
 * Class AdminUserPasswordListener.
 * @author Anton Prokhorov
 */
class AdminUserPasswordListener
{

    /**
     * @var UserPasswordEncoder
     */
    protected $passwordEncoder;

    /**
     * UserPasswordListener constructor.
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof AdminUser) {
            $entity->setPassword($this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword()));
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof AdminUser) {
            $this->handleEvent($entity);
        }
    }

    private function handleEvent(AdminUser $user)
    {
        if (!$user->getPlainPassword()) {
            return;
        }
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
    }

}