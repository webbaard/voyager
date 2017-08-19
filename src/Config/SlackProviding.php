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
    private function getSlackService($config): SlackService
    {
        $token = $config['token'];

        if (!$token) {
            throw new SlackConfigNotFound();
        }

        return new SlackService($token);
    }
}