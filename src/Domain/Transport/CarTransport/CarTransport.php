<?php

namespace Printdeal\Voyager\Domain\Transport\CarTransport;

use Prooph\EventSourcing\AggregateRoot;

class CarTransport extends AggregateRoot
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}