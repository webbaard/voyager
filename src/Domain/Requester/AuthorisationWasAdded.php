<?php


namespace Printdeal\Voyager\Domain\Requester;

use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Prooph\EventSourcing\AggregateChanged;

class AuthorisationWasAdded extends AggregateChanged
{
    const PAYLOAD_AUTHORISATION = 'authorisation';

    /**
     * @param RequesterId $id
     * @param AuthorisationId $authorisationId
     * @return AuthorisationWasAdded
     */
    public static function from(RequesterId $id, AuthorisationId $authorisationId)
    {
        return self::occur(
            (string)$id,
            [
                self::PAYLOAD_AUTHORISATION => (string)$authorisationId,
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
}