<?php

namespace Printdeal\Voyager\Domain\Conference;

use Prooph\EventSourcing\AggregateRoot;

class Conference extends AggregateRoot
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}