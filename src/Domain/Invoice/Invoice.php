<?php

namespace Printdeal\Voyager\Domain\Invoice;


use Printdeal\Voyager\Domain\Subject\Subject;
use Prooph\EventSourcing\AggregateRoot;

class Invoice extends AggregateRoot implements Subject
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}