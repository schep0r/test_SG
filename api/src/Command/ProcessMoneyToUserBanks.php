<?php

namespace App\Command;

use App\Entity\Prize;
use App\Factory\EntityManagerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessMoneyToUserBanks extends Command
{
    protected static $defaultName = 'app:send:money-to-bank';

    protected static $defaultDescription = 'Send users money prizes to their banks.';

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit for record process at once', 2)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = $input->getArgument('limit');

        $em = (new EntityManagerFactory())->getEm();

        $records = $em->getRepository(Prize::class)->findMoneyToSend($limit);

        /** @var Prize $record */
        foreach ($records as $record) {
            $record->setProcessed(true);
        }

        $em->flush();

        $output->writeln("Processed done. Records updated: " . count($records));

        return Command::SUCCESS;
    }
}