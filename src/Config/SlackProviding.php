<?php

namespace Printdeal\Voyager\Config;

use Printdeal\Voyager\Exceptions\SlackConfigNotFound;
use Printdeal\Voyager\Infrastructure\Service\SlackService;

trait SlackProviding
{
    /**
     * @return SlackService
     * @throws SlackConfigNotFound
     */
    private function getSlackService(): SlackService
    {
        $loadedConfig = include(__DIR__ . '/../../config/slack.php');
        $token = $loadedConfig['token'];

        if (!$token) {
            throw new SlackConfigNotFound();
        }

        return new SlackService($token);
    }
}