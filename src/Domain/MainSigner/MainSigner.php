<?php

namespace Printdeal\Voyager\Domain\Manager;

use Prooph\EventSourcing\AggregateRoot;

class MainSigner extends AggregateRoot
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}