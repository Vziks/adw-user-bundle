<?php

namespace ADW\UserBundle\Command;

use ADW\UserBundle\Entity\AdminUser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Persisters\PersisterException;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use ADW\ConfigBundle\Entity\AllowIp;
use ADW\ConfigBundle\Entity\ConfigSite;
use Doctrine\ORM\Tools\SchemaTool;

class CreateAdminCommand extends ContainerAwareCommand
{


    /**
     * @var EntityManager $em
     */
    private $em;


    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('adw:create:admin')
            ->setDescription('Add admin user')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument('password', null, 'The password of the user.')
            ->setHelp('This command allows you to create a user...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = $this->em->getRepository('AppBundle:AdminUser')->findOneBy(['email' => $input->getArgument('username')]);

        if ($user) {
            $output->writeln('-------------------------');
            $output->writeln('<error>User already exists!</error>');
        } else {

            $password = null;

            $newAdminRecord = new AdminUser($input->getArgument('username'));

            if ($input->getArgument('password')) {
                $newAdminRecord->setPlainPassword($input->getArgument('password'));
            } else {
                $password = $this->randString(8);
                $newAdminRecord->setPlainPassword($password);
            }

            $newAdminRecord->setRoles(['ROLE_ADMIN','ROLE_SUPER_ADMIN']);

            $this->em->persist($newAdminRecord);

            try {
                $this->em->flush();
                $output->writeln('-------------------------');
                $output->writeln('<info>Add user record</info>');
                if ($password) {
                    $output->writeln('<info>User password: '.$password.'</info>');
                }
            } catch (PersisterException $e) {
                $output->writeln('-------------------------');
                $output->writeln('<error>Error write record!</error>');
            }

        }

    }

    /**
     * @param $length
     * @return bool|string
     */
    protected function randString($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,$length);
    }

}