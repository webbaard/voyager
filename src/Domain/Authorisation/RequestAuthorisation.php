<?php

namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Requester\RequesterId;
use Prooph\Common\Messaging\Command;

class RequestAuthorisation extends Command
{
    /**
     * @var RequesterId
     */
    private $requesterId;

    /**
     * @var Description
     */
    private $description;

    /**
     * RequestAuthorisation constructor.
     * @param RequesterId $requesterId
     * @param Description $description
     */
    private function __construct(RequesterId $requesterId, Description $description)
    {
        $this->init();

        $this->requesterId = $requesterId;
        $this->description = $description;
    }

    /**
     * @param RequesterId $requesterId
     * @param Description $description
     * @return RequestAuthorisation
     */
    public static function from(RequesterId $requesterId, Description $description)
    {
        return new self($requesterId, $description);
    }

    /**
     * @return RequesterId
     */
    public function requester()
    {
        return $this->requesterId;
    }

    /**
     * @return Description
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            AuthorisationRequested::PAYLOAD_REQUESTER => $this->requester(),
            AuthorisationRequested::PAYLOAD_DESCRIPTION => $this->description()
        ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->requesterId = $payload[AuthorisationRequested::PAYLOAD_REQUESTER];
        $this->description = $payload[AuthorisationRequested::PAYLOAD_DESCRIPTION];
    }
}