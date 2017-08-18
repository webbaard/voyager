<?php


namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Requester\RequesterId;
use Prooph\EventSourcing\AggregateChanged;
use Rhumsaa\Uuid\Uuid;

class AuthorisationRequested extends AggregateChanged
{
    const PAYLOAD_AUTHORISATION = 'authorisation';
    const PAYLOAD_REQUESTER = 'requester';
    const PAYLOAD_DESCRIPTION = 'description';

    /**
     * @param AuthorisationId $id
     * @param RequesterId $requesterId
     * @param string $description
     * @return AuthorisationRequested
     */
    public static function from(AuthorisationId $id, RequesterId $requesterId, string $description)
    {
        return self::occur(
            (string)$id,
            [
                self::PAYLOAD_AUTHORISATION => (string)$id,
                self::PAYLOAD_REQUESTER => (string)$requesterId,
                self::PAYLOAD_DESCRIPTION => $description
            ]
        );
    }

    /**
     * @return AuthorisationId
     */
    public function authorisation(): AuthorisationId
    {
        return AuthorisationId::fromString($this->payload[self::PAYLOAD_AUTHORISATION]);
    }

    /**
     * @return RequesterId
     */
    public function requester(): RequesterId
    {
        return RequesterId::fromString($this->payload[self::PAYLOAD_REQUESTER]);
    }

    /**
     * @return Description
     */
    public function description(): Description
    {
        return Description::fromString($this->payload[self::PAYLOAD_DESCRIPTION]);
    }
}