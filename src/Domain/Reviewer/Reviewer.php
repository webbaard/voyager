<?php

namespace Printdeal\Voyager\Domain\Reviewer;


use Printdeal\Voyager\Domain\Signer\Signer;
use Prooph\EventSourcing\AggregateRoot;

class Reviewer extends AggregateRoot implements Signer
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}