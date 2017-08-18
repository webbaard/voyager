<?php

namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Requester\RequesterId;
use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

class RequestAuthorisation extends Command
{
    /**
     * @var AuthorisationId
     */
    private $authorisationId;

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
        $this->authorisationId = AuthorisationId::fromString(Uuid::uuid4());
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
     * @return AuthorisationId
     */
    public function authorisation()
    {
        return $this->authorisationId;
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
            AuthorisationRequested::PAYLOAD_AUTHORISATION => $this->authorisation(),
            AuthorisationRequested::PAYLOAD_REQUESTER => $this->requester(),
            AuthorisationRequested::PAYLOAD_DESCRIPTION => $this->description()
        ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->authorisationId = $payload[AuthorisationRequested::PAYLOAD_AUTHORISATION];
        $this->requesterId = $payload[AuthorisationRequested::PAYLOAD_REQUESTER];
        $this->description = $payload[AuthorisationRequested::PAYLOAD_DESCRIPTION];
    }
}