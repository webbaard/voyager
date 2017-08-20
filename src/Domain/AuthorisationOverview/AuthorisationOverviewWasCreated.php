<?php


namespace Printdeal\Voyager\Domain\AuthorisationOverview;

use Prooph\EventSourcing\AggregateChanged;

class AuthorisationOverviewWasCreated extends AggregateChanged
{
    const PAYLOAD_AUTHORISATION_OVERVIEW = 'authorisation-overview';
    const PAYLOAD_OWNER = 'owner';


    /**
     * @param AuthorisationOverviewId $id
     * @param Owner $owner
     * @return static
     */
    public static function from(AuthorisationOverviewId $id, Owner $owner)
    {
        return self::occur(
            (string)$id,
            [
                self::PAYLOAD_AUTHORISATION_OVERVIEW => (string)$id,
                self::PAYLOAD_OWNER => (string)$owner,
            ]
        );
    }

    /**
     * @return AuthorisationOverviewId
     */
    public function authorisationOverview(): AuthorisationOverviewId
    {
        return AuthorisationOverviewId::fromString($this->payload[self::PAYLOAD_AUTHORISATION_OVERVIEW]);
    }

    /**
     * @return Owner
     */
    public function owner(): Owner
    {
        if(!isset($this->payload[self::PAYLOAD_OWNER])) {
            return Owner::fromString('');
        }
        return Owner::fromString($this->payload[self::PAYLOAD_OWNER]);
    }
}