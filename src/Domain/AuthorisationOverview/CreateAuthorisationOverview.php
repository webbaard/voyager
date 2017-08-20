<?php

namespace Printdeal\Voyager\Domain\AuthorisationOverview;

use Printdeal\Voyager\Domain\Authorisation\Amount;
use Printdeal\Voyager\Domain\Authorisation\Supplier;
use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

class CreateAuthorisationOverview extends Command
{
    /**
     * @var AuthorisationOverviewId
     */
    private $authorisationOverviewId;

    /**
     * @var Owner
     */
    private $owner;

    private function __construct(Owner $owner)
    {
        $this->init();
        $this->authorisationOverviewId = AuthorisationOverviewId::fromString(Uuid::uuid4());
        $this->owner = $owner;
    }

    /**
     * @param Owner $owner
     * @return CreateAuthorisationOverview
     */
    public static function from(Owner $owner)
    {
        return new self($owner);
    }

    /**
     * @return AuthorisationOverviewId
     */
    public function authorisationOverview()
    {
        return $this->authorisationOverviewId;
    }

    /**
     * @return Owner
     */
    public function owner()
    {
        return $this->owner;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            AuthorisationOverview::AGGREGATE_NAME => (string)$this->authorisationOverviewId,
            AuthorisationOverviewWasCreated::PAYLOAD_OWNER => (string)$this->owner,
  ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->authorisationOverviewId = AuthorisationOverviewId::fromString($payload[AuthorisationOverview::AGGREGATE_NAME]);
        $this->owner = Owner::fromString($payload[AuthorisationOverviewWasCreated::PAYLOAD_OWNER]);
    }
}