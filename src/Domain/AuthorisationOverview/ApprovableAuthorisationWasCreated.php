<?php


namespace Printdeal\Voyager\Domain\AuthorisationOverview;


use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Prooph\EventSourcing\AggregateChanged;

class ApprovableAuthorisationWasCreated extends AggregateChanged
{
    const PAYLOAD_AUTHORISATION = 'authorisation';

    /**
     * @param AuthorisationOverviewId $id
     * @param AuthorisationId $authorisationId
     * @return ApprovableAuthorisationWasCreated
     */
    public static function from(AuthorisationOverviewId $id, AuthorisationId $authorisationId)
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
    public function authorisation()
    {
        return AuthorisationId::fromString($this->payload[self::PAYLOAD_AUTHORISATION]);
    }
}