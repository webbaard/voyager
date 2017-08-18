<?php

namespace Printdeal\Voyager\Configs;

use Printdeal\Voyager\Exceptions\SlackConfigNotFound;

class SlackConfig
{
    private $token;

    public function __construct()
    {
        $loadedConfig = include(__DIR__ . '/../../config/slack.php');
        $this->token = $loadedConfig['token'];
    }

    public function getToken(): string
    {
        if (!$this->token) {
            throw new SlackConfigNotFound();
        }
        return $this->token;
    }
}