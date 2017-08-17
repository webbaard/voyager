<?php

namespace Printdeal\Voyager\Domain\Attendee;

use Prooph\EventSourcing\AggregateRoot;

class Attendee extends AggregateRoot
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}