<?php


namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\AuthorisationOverview\Owner;
use Printdeal\Voyager\Domain\Reviewer\ReviewerId;
use Printdeal\Voyager\Domain\Subject\SubjectId;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Adapter\Exception\RuntimeException;
use Rhumsaa\Uuid\Uuid;

class AuthorisationWasRejected extends AggregateChanged
{
    const PAYLOAD_REVIEWER = 'reviewer';

    /**
     * @param AuthorisationId $id
     * @param ReviewerId $reviewerId
     * @return AuthorisationWasRejected
     */
    public static function from(AuthorisationId $id, ReviewerId $reviewerId)
    {
        return self::occur(
            (string)$id,
            [
                self::PAYLOAD_REVIEWER => (string)$reviewerId,
            ]
        );
    }

    /**
     * @return ReviewerId
     */
    public function reviewer()
    {
        return ReviewerId::fromString($this->payload[self::PAYLOAD_REVIEWER]);
    }
}