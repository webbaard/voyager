<?php

namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Requester\RequesterId;
use Printdeal\Voyager\Domain\Reviewer\ReviewerId;
use Printdeal\Voyager\Domain\Signer\Signer;
use Printdeal\Voyager\Domain\Signer\SignerId;
use Printdeal\Voyager\Domain\Subject\SubjectId;
use Prooph\EventSourcing\AggregateRoot;
use Rhumsaa\Uuid\Uuid;

class Authorisation extends AggregateRoot
{
    const AGGREGATE_NAME = 'authorisation';

    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var RequesterId
     */
    private $requesterId;

    /**
     * @var Supplier
     */
    private $supplier;

    /**
     * @var CostCenter;
     */
    private $costCenter;

    /**
     * @var Commodity
     */
    private $commodity;

    /**
     * @var ProjectId
     */
    private $projectId;

    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var DueDate
     */
    private $dueDate;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var SignerId[]
     */
    private $reviewerIds = [];

    /**
     * @var Approval[]
     */
    private $approvals = [];

    /**
     * @var Rejection[]
     */
    private $rejections = [];

    /**
     * @var SubjectId[]
     */
    private $subjectIds = [];

    /**
     * @param AuthorisationId $authorisationId
     * @param RequesterId $requesterId
     * @param Supplier $supplier
     * @param CostCenter $costCenter
     * @param Commodity $commodity
     * @param ProjectId $projectId
     * @param Amount $amount
     * @param DueDate $dueDate
     * @param Description $description
     * @return Authorisation
     */
    public static function request(AuthorisationId $authorisationId, RequesterId $requesterId, Supplier $supplier, CostCenter $costCenter, Commodity $commodity, ProjectId $projectId, Amount $amount, DueDate $dueDate, Description $description): self
    {
        $self = new self(Uuid::fromString($authorisationId));
        $self->recordThat(
            AuthorisationRequested::from(
                $authorisationId,
                $requesterId,
                $supplier,
                $costCenter,
                $commodity,
                $projectId,
                $amount,
                $dueDate,
                $description
            )
        );
        return $self;
    }

    /**
     * @param SubjectId $subjectId
     */
    public function addSubject(SubjectId $subjectId)
    {
        $this->recordThat(
            SubjectWasAdded::from(
                AuthorisationId::fromString($this->id),
                $subjectId
            )
        );
    }
    
    public function approve(ReviewerId $reviewerId)
    {
        $this->recordThat(
            AuthorisationWasApproved::from(
                AuthorisationId::fromString($this->id),
                $reviewerId
            )
        );
    }    
    
    public function reject(ReviewerId $reviewerId)
    {
        $this->recordThat(
            AuthorisationWasRejected::from(
                AuthorisationId::fromString($this->id),
                $reviewerId
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
        return AuthorisationId::fromString($this->id);
    }

    /**
     * @return RequesterId
     */
    public function requester(): RequesterId
    {
        return $this->requesterId;
    }

    /**
     * @return Supplier
     */
    public function supplier()
    {
        return $this->supplier;
    }

    /**
     * @return CostCenter
     */
    public function costCenter()
    {
        return $this->costCenter;
    }

    /**
     * @return Commodity
     */
    public function commodity()
    {
        return $this->commodity;
    }

    /**
     * @return ProjectId
     */
    public function projectId()
    {
        return $this->projectId;
    }

    /**
     * @return Amount
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return DueDate
     */
    public function dueDate()
    {
        return $this->dueDate;
    }

    /**
     * @return Signer[]
     */
    public function reviewers(): array
    {
        return $this->reviewerIds;
    }

    /**
     * @return Approval[]
     */
    public function approvals(): array
    {
        return $this->approvals;
    }

    /**
     * @return Description
     */
    public function description(): Description
    {
        return $this->description;
    }

    /**
     * @return Rejection[]
     */
    public function rejections(): array
    {
        return $this->rejections;
    }

    /**
     * @return SubjectId[]
     */
    public function subjects(): array
    {
        return $this->subjectIds;
    }

    /**
     * @param AuthorisationRequested $event
     */
    protected function whenAuthorisationRequested(AuthorisationRequested $event)
    {
        $this->id = $event->aggregateId();
        $this->requesterId = $event->requester();
        $this->supplier = $event->supplier();
        $this->costCenter = $event->costCenter();
        $this->commodity = $event->commodity();
        $this->projectId = $event->projectId();
        $this->amount = $event->amount();
        $this->dueDate = $event->dueDate();
        $this->description = $event->description();
    }

    /**
     * @param SubjectWasAdded $event
     */
    protected function whenSubjectWasAdded(SubjectWasAdded $event)
    {
        $this->subjectIds[] = $event->subject();
    }
    
    protected function whenAuthorisationWasApproved(AuthorisationWasApproved $event)
    {
        $this->approvals[] = $event->reviewer();
    }    
    
    protected function whenAuthorisationWasRejected(AuthorisationWasRejected $event)
    {
        $this->rejections[] = $event->reviewer();
    }
}