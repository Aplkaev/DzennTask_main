<?php

namespace App\Command;

use App\Entity\OutboxEvent;
use App\Service\EventPublisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// php bin/console app:outbox:process
#[AsCommand(
    name: 'app:outbox:process',
    description: 'Process outbox events and send to RabbitMQ'
)]
class OutboxProcessor extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private EventPublisher $publisher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $events = $this->em
            ->getRepository(OutboxEvent::class)
            ->findBy(['processedAt' => null], ['id' => 'ASC'], 100);

        foreach ($events as $event) {
            try {
                $this->publisher->publish($event);

                $event->markProcessed();
            } catch (\Throwable $e) {
                $output->writeln('Error: ' . $e->getMessage());
            }
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}