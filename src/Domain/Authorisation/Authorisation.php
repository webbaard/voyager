<?php

namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Requester\RequesterId;
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
     * @var SignerId[]
     */
    private $reviewerIds;

    /**
     * @var Approval[]
     */
    private $approvals;

    /**
     * @var Rejection[]
     */
    private $rejections;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var SubjectId[]
     */
    private $subjectIds;

    /**
     * @param AuthorisationId $authorisationId
     * @param RequesterId $requesterId
     * @param Description $description
     * @return Authorisation
     */
    public static function request(AuthorisationId $authorisationId, RequesterId $requesterId, Description $description): self
    {
        $self = new self(Uuid::uuid4());
        $self->recordThat(
            AuthorisationRequested::from(
                $authorisationId,
                $requesterId,
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
     * @return RequesterId
     */
    public function getRequester(): RequesterId
    {
        return $this->requesterId;
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
        $this->description = $event->description();
    }

    /**
     * @param SubjectWasAdded $event
     */
    protected function whenSubjectWasAdded(SubjectWasAdded $event)
    {
        $this->subjectIds[] = $event->subject();
    }
}