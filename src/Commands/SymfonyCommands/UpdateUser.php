<?php

namespace App\Commands\SymfonyCommands;

use App\Commands\CreateUserCommand;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUser extends Command
{
    public function __construct(
        private UserRepositoryInterface $usersRepository,
        private CreateUserCommand $createUserCommand
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('user:update')
            ->setDescription('Update a user')
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'Id of a user to update'
            )
            ->addOption(
                'first-name',
                'f',
                InputOption::VALUE_OPTIONAL,
                'First name',
            )
            ->addOption(
                'last-name',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Last name',
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $firstName = $input->getOption('first-name');
        $lastName = $input->getOption('last-name');

        if (empty($firstName) && empty($lastName)) {
            $output->writeln('Nothing to update');
            return Command::SUCCESS;
        }

        $user = $this->usersRepository->findById($input->getArgument('id'));

        $firstName = $firstName ?? $user->getFirstName();
        $lastName = $lastName ?? $user->getLastName();

        $user
            ->setLastName($lastName)
            ->setFirstName($firstName);

        $this->createUserCommand->handle($user);
        $output->writeln(sprintf("User updated: %d",  $user->getId()));

        return Command::SUCCESS;
    }

}