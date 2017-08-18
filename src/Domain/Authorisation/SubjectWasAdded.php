<?php


namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Subject\SubjectId;
use Prooph\EventSourcing\AggregateChanged;
use Rhumsaa\Uuid\Uuid;

class SubjectWasAdded extends AggregateChanged
{
    const PAYLOAD_SUBJECT = 'subject';

    /**
     * @param Uuid $id
     * @param SubjectId $subjectId
     * @return SubjectWasAdded
     */
    public static function from(Uuid $id, SubjectId $subjectId)
    {
        return self::occur(
            (string)$id,
            [
                self::PAYLOAD_SUBJECT => (string)$subjectId,
            ]
        );
    }

    /**
     * @return SubjectId
     */
    public function subject()
    {
        return SubjectId::fromString($this->payload[self::PAYLOAD_SUBJECT]);
    }
}