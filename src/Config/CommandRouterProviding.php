<?php


namespace Printdeal\Voyager\Config;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;

trait CommandRouterProviding
{
    /**
     * @param CommandBus $commandBus
     * @return CommandRouter
     */
    private function getCommandRouter(CommandBus $commandBus)
    {
        $commandRouter = new CommandRouter();

        $commandRouter->attach($commandBus->getActionEventEmitter());

        return $commandRouter;
    }
}