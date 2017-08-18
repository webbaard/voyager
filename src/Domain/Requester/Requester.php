<?php
/**
 * Created by PhpStorm.
 * User: tim.huijzers
 * Date: 18-8-2017
 * Time: 10:15
 */

namespace Printdeal\Voyager\Domain\Requester;


use Printdeal\Voyager\Domain\Signer\Signer;
use Prooph\EventSourcing\AggregateRoot;

class Requester extends AggregateRoot implements Signer
{
    /**
     * @return string representation of the unique identifier of the aggregate root
     */
    protected function aggregateId()
    {
        // TODO: Implement aggregateId() method.
    }
}