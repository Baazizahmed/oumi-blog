<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:maintenance',
    description: 'Activate or not the maintenance mode',
)]
class MaintenanceCommand extends Command
{
    protected SymfonyStyle $io;

    public function __construct(
        #[Autowire('%kernel.project_dir%')] protected string $projectDir,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('mode', InputArgument::REQUIRED, 'Mode on | off')
        ;
    }

    /**
     * Initializes the command after the input has been bound and before the input
     * is validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @see InputInterface::bind()
     * @see InputInterface::validate()
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mode = $input->getArgument('mode');
        $maintenanceFile = $this->projectDir.'/var/maintenance.lock';

        if ('on' === $mode) {
            if (!file_exists($maintenanceFile)) {
                touch($maintenanceFile);
                $this->io->success('Le mode maintenace est activé');
            } else {
                $this->io->warning('Le mode maintenace est déjà activé');
            }
        } elseif ('off' === $mode) {
            if (file_exists($maintenanceFile)) {
                unlink($maintenanceFile);
                $this->io->success('Le mode maintenace est déactivé');
            } else {
                $this->io->warning('Le mode maintenace n\'est pas activé');
            }
        } else {
            $this->io->error('Un probleme avec le mode: Veuillez saisir "on" ou "off".');

            return Command::INVALID;
        }

        return Command::SUCCESS;
    }
}
