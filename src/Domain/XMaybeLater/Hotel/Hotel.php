<?php

namespace Printdeal\Voyager\Domain\Hotel;

use Prooph\EventSourcing\AggregateRoot;

class Hotel extends AggregateRoot
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}