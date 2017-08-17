<?php

namespace Printdeal\Voyager\Domain\Transport\TrainTransport;

use Prooph\EventSourcing\AggregateRoot;

class TrainTransport extends AggregateRoot
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}