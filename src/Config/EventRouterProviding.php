<?php


namespace Printdeal\Voyager\Config;


use Prooph\EventStore\EventStore;
use Prooph\EventStoreBusBridge\EventPublisher;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\Plugin\Router\EventRouter;

trait EventRouterProviding
{
    /**
     * @param EventStore $eventStore
     * @return EventRouter
     */
    private function getEventRouter(EventStore $eventStore)
    {
        $eventBus = new EventBus();

        $eventRouter = new EventRouter();

        $eventRouter->attach($eventBus->getActionEventEmitter());

        (new EventPublisher($eventBus))->setUp($eventStore);

        return $eventRouter;
    }
}