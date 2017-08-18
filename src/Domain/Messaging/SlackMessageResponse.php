<?php

namespace Printdeal\Voyager\Domain\Messaging;

class SlackMessageResponse
{
    const RESULT_ERROR = 'error';
    const RESULT_OK = 'ok';

    /** @var  \Frlnc\Slack\Contracts\Http\Response */
    private $rawResponse;
    /**
     * SlackResponse constructor.
     * @param \Frlnc\Slack\Contracts\Http\Response $rawResponse
     */
    public function __construct(\Frlnc\Slack\Contracts\Http\Response $rawResponse)
    {
        $this->rawResponse = $rawResponse;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        if (!$this->rawResponse) {
            return self::RESULT_ERROR;
        }
        if ($this->rawResponse->getStatusCode() != 200) {
            return self::RESULT_ERROR;
        }
        if ($this->getSlackBody()['ok'] == false) {
            return self::RESULT_ERROR;
        }

        return self::RESULT_OK;
    }

    /**
     * @return array|string
     */
    public function getSlackBody()
    {
        return $this->rawResponse->getBody();
    }

    /**
     * @return string
     */
    public function getSlackHeaders()
    {
        return $this->rawResponse->getBody();
    }
}