<?php

namespace App\EventSubscriber;

use App\Entity\BaseEntity;
use App\Entity\Task;
use App\Entity\OutboxEvent;
use App\Entity\PublishableEventEntity;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\EntityManagerInterface;

#[AsDoctrineListener(event: Events::onFlush)]
class EntitySubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof PublishableEventEntity) {
                continue;
            }

            $this->createEvent($em, $entity, 'created');
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof PublishableEventEntity) {
                continue;
            }

            $this->createEvent($em, $entity, 'updated');
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if (!$entity instanceof PublishableEventEntity) {
                continue;
            }

            $this->createEvent($em, $entity, 'deleted');
        }
    }

    private function createEvent(EntityManagerInterface $em, PublishableEventEntity $entity, string $type): void
    {
        $event = new OutboxEvent(
        );
        $reflection = new \ReflectionClass($entity);

        $entityName = strtolower($reflection->getShortName());

        $event->setType($entityName.'.'.$type);
        $event->setPayload($entity->toEventPayload());
        $em->persist($event);

        $meta = $em->getClassMetadata(OutboxEvent::class);
        $em->getUnitOfWork()->computeChangeSet($meta, $event);
    }
}