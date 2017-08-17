<?php

namespace Printdeal\Voyager\Domain\Transport\PlaneTransport;

use Prooph\EventSourcing\AggregateRoot;

class PlaneTransport extends AggregateRoot
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}