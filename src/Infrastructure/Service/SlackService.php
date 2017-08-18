<?php

namespace Printdeal\Voyager\Infrastructure\Service;

use Frlnc\Slack\Core\Commander;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Http\SlackResponseFactory;
use Printdeal\Voyager\Configs\SlackConfig;
use Printdeal\Voyager\Domain\Messaging\SlackMessageResponse;

class SlackService
{
    /** @var string */
    private $slackToken;

    /** @var Commander */
    private $commander;

    /**
     * SlackService constructor.
     * @param SlackConfig $slackConfig
     */
    public function __construct(
        SlackConfig $slackConfig
    ) {
        $this->slackToken = $slackConfig->getToken();

        $curlInteractor = new CurlInteractor;
        $curlInteractor->setResponseFactory(new SlackResponseFactory);
        $this->commander = new Commander($this->slackToken, $curlInteractor);
    }

    /**
     * @param string $to
     * @param string $message
     * @return SlackMessageResponse
     */
    public function sendMessage(
        string $to,
        string $message
    ): SlackMessageResponse {
        $slackResponse = $this->commander->execute('chat.postMessage', [
            'channel' => $to,
            'text'    => $message,
            'as_user' => true
        ]);

        return new SlackMessageResponse($slackResponse);
    }
}
