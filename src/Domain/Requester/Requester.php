<?php
/**
 * Created by PhpStorm.
 * User: tim.huijzers
 * Date: 18-8-2017
 * Time: 10:15
 */

namespace Printdeal\Voyager\Domain\Requester;


use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Printdeal\Voyager\Domain\Employee\EmployeeName;
use Printdeal\Voyager\Domain\Signer\Signer;
use Prooph\EventSourcing\AggregateRoot;
use Rhumsaa\Uuid\Uuid;

class Requester extends AggregateRoot
{
    const AGGREGATE_NAME = 'requester';

    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var AuthorisationId[]
     */
    private $authorisations;

    /**
     * @var EmployeeName
     */
    private $employee;

    /**
     * @param RequesterId $requesterId
     * @param EmployeeName $employeeName
     * @return Requester
     */
    public static function request(RequesterId $requesterId, EmployeeName $employeeName)
    {
        $self = new self(Uuid::fromString($requesterId));
        $self->recordThat(
            RequesterCreated::from(
                $requesterId,
                $employeeName
            )
        );
        return $self;
    }

    /**
     * @param AuthorisationId $authorisationId
     */
    public function addAuthorisation(AuthorisationId $authorisationId)
    {
        $this->recordThat(
            AuthorisationWasAdded::from(
                RequesterId::fromString($this->id),
                $authorisationId
            )
        );
    }

    /**
     * @return string
     */
    protected function aggregateId()
    {
        return (string)$this->id;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->aggregateId();
    }

    /**
     * @return EmployeeName
     */
    public function employee(): EmployeeName
    {
        return $this->employee;
    }

    /**
     * @param RequesterCreated $event
     */
    protected function whenRequesterCreated(RequesterCreated $event)
    {
        $this->id = $event->aggregateId();
        $this->employee = $event->employee();
    }

    /**
     * @param AuthorisationWasAdded $event
     */
    protected function whenRequesterWasAdded(AuthorisationWasAdded $event)
    {
        $this->authorisations[] = $event->authorisation();
    }
}