<?php

namespace Printdeal\Voyager\Domain\Requester;

use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Prooph\Common\Messaging\Command;

class AddAuthorisation extends Command
{
    /**
     * @var RequesterId
     */
    private $requesterId;
    /**
     * @var AuthorisationId
     */
    private $authorisation;

    /**
     * AddAuthorisation constructor.
     * @param RequesterId $requesterId
     * @param AuthorisationId $authorisationId
     */
    private function __construct(RequesterId $requesterId, AuthorisationId $authorisationId)
    {
        $this->init();

        $this->requesterId = $requesterId;
        $this->authorisation = $authorisationId;
    }

    /**
     * @param RequesterId $requesterId
     * @param AuthorisationId $authorisationId
     * @return AddAuthorisation
     */
    public static function from(RequesterId $requesterId, AuthorisationId $authorisationId)
    {
        return new self($requesterId, $authorisationId);
    }

    /**
     * @return RequesterId
     */
    public function requester()
    {
        return $this->requesterId;
    }

    /**
     * @return AuthorisationId
     */
    public function authorisation()
    {
        return $this->authorisation;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            Requester::AGGREGATE_NAME => (string)$this->requesterId,
            AuthorisationWasAdded::PAYLOAD_AUTHORISATION => (string)$this->authorisation
        ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->requesterId = RequesterId::fromString($payload[Requester::AGGREGATE_NAME]);
        $this->authorisation = AuthorisationId::fromString($payload[AuthorisationWasAdded::PAYLOAD_AUTHORISATION]);
    }
}