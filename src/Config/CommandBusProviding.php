<?php


namespace Printdeal\Voyager\Config;


use Prooph\EventStore\EventStore;
use Prooph\EventStoreBusBridge\TransactionManager;
use Prooph\ServiceBus\CommandBus;

trait CommandBusProviding
{
    /**
     * @param EventStore $eventStore
     * @return CommandBus
     */
    private function getCommandBus(EventStore $eventStore)
    {
        // Command bus setup
        $commandBus = new CommandBus();
        $transactionManager = new TransactionManager();

        $transactionManager->setUp($eventStore);
        $commandBus->utilize($transactionManager);
        return $commandBus;
    }
}