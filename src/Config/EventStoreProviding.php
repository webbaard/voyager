<?php


namespace Printdeal\Voyager\Config;


use Doctrine\DBAL\Connection;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\Common\Messaging\NoOpMessageConverter;
use Prooph\EventStore\Adapter\Doctrine\DoctrineEventStoreAdapter;
use Prooph\EventStore\Adapter\PayloadSerializer\JsonPayloadSerializer;
use Prooph\EventStore\EventStore;

trait EventStoreProviding
{
    /**
     * @param Connection $connection
     * @return EventStore
     */
    private function getEventStore(Connection $connection)
    {
        $eventStore = new EventStore(
            new DoctrineEventStoreAdapter(
                $connection,
                new FQCNMessageFactory(),
                new NoOpMessageConverter(),
                new JsonPayloadSerializer()
            ),
            new ProophActionEventEmitter()
        );
        return $eventStore;
    }
}