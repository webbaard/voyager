<?php


namespace Printdeal\Voyager\Config;


use Doctrine\DBAL\Driver\Connection;
use Printdeal\Voyager\Infrastructure\Service\SlackMessager;
use Printdeal\Voyager\Infrastructure\Service\SlackService;

trait MessageProviding
{
    private function getMessageProvider(SlackService $slackService, Connection $connection)
    {
        return new SlackMessager(
            $slackService,
            $connection
        );
    }
}