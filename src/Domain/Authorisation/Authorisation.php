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
     * Authorisation constructor.
     * @param $id
     */
    protected function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param RequesterId $requesterId
     * @param Description $description
     * @param array $subjectIds
     * @return Authorisation
     */
    public static function request(RequesterId $requesterId, Description $description, array $subjectIds): self
    {
        $self = new self(Uuid::uuid4());
        $self->recordThat(
            AuthorisationRequested::from(
                $self->id,
                $requesterId,
                $description
            )
        );
        foreach ($subjectIds as $subjectId) {
            $self->recordThat(
                SubjectWasAdded::from(
                    $self->id,
                    $subjectId
                )
            );
        }
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