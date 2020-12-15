<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    private EntityManagerInterface $em;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('User Creator');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if ($email) {
            $io->note(sprintf('You passed an argument: %s', $email));
        }

        if ($password) {
            $io->note(sprintf('You passed an argument: %s', $password));
        }

        $user = (new User())->setEmail($email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password))
            ->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        $io->success('create a user.');

        return Command::SUCCESS;
    }
}
