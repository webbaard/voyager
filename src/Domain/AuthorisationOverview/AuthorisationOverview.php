<?php


namespace Printdeal\Voyager\Domain\AuthorisationOverview;


use Printdeal\Voyager\Domain\Authorisation\Amount;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Printdeal\Voyager\Domain\Authorisation\Supplier;
use Prooph\EventSourcing\AggregateRoot;
use Rhumsaa\Uuid\Uuid;

class AuthorisationOverview extends AggregateRoot
{
    const AGGREGATE_NAME = 'authorisation-overview';

    private $id;

    /**
     * @var Owner
     */
    private $owner;

    /**
     * @var AuthorisationId[]
     */
    private $requestedAuthorisations = [];

    /**
     * @var AuthorisationId[]
     */
    private $approvableAuthorisations = [];

    /**
     * @param AuthorisationOverviewId $authorisationOverviewId
     * @param Owner $owner
     * @return AuthorisationOverview
     */
    public static function create(AuthorisationOverviewId $authorisationOverviewId, Owner $owner)
    {
        $self = new self(Uuid::fromString($authorisationOverviewId));
        $self->recordThat(
            AuthorisationOverviewWasCreated::from(
                $authorisationOverviewId,
                $owner
            )
        );
        return $self;
    }

    public function addRequestedAuthorisation(AuthorisationId $authorisationId)
    {
        $this->recordThat(
            RequestedAuthorisationWasCreated::from(
                AuthorisationOverviewId::fromString($this->id),
                $authorisationId
            )
        );
    }

    public function addApprovableAuthorisation(AuthorisationId $authorisationId)
    {
        $this->recordThat(
            ApprovableAuthorisationWasCreated::from(
                AuthorisationOverviewId::fromString($this->id),
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
     * @return AuthorisationOverviewId
     */
    public function id(): AuthorisationOverviewId
    {
        return AuthorisationOverviewId::fromString($this->id);
    }

    public function owner(): Owner
    {
        return $this->owner;
    }

    /**
     * @return AuthorisationId[]
     */
    public function requestedAuthorisations(): array
    {
        return $this->requestedAuthorisations;
    }

    /**
     * @return array
     */
    public function approvableAuthorisations(): array
    {
        return $this->approvableAuthorisations;
    }

    /**
     * @param AuthorisationOverviewWasCreated $event
     */
    protected function whenAuthorisationOverviewWasCreated(AuthorisationOverviewWasCreated $event)
    {
        $this->id = Uuid::fromString($event->authorisationOverview());
        $this->owner = $event->owner();
    }

    /**
     * @param RequestedAuthorisationWasCreated $event
     */
    protected function whenRequestedAuthorisationWasCreated(RequestedAuthorisationWasCreated $event)
    {
        $this->requestedAuthorisations[(string)$event->authorisation()] = $event->authorisation();
    }
    /**
     * @param ApprovableAuthorisationWasCreated $event
     */
    protected function whenApprovableAuthorisationWasCreated(ApprovableAuthorisationWasCreated $event)
    {
        $this->approvableAuthorisations[(string)$event->authorisation()] = $event->authorisation();
    }
}