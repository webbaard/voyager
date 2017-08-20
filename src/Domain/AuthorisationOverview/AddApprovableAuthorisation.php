<?php


namespace Printdeal\Voyager\Domain\AuthorisationOverview;


use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Prooph\Common\Messaging\Command;

class AddApprovableAuthorisation extends Command
{
    private $authorisationOverviewId;

    private $authorisationId;

    private function __construct(AuthorisationOverviewId $authorisationOverviewId, AuthorisationId $authorisationId)
    {
        $this->init();

        $this->authorisationOverviewId = $authorisationOverviewId;
        $this->authorisationId = $authorisationId;
    }

    public static function from(AuthorisationOverviewId $authorisationOverviewId, AuthorisationId $authorisationId)
    {
        return new self($authorisationOverviewId, $authorisationId);
    }

    public function authorisationOverview()
    {
        return $this->authorisationOverviewId;
    }

    public function authorisation()
    {
        return $this->authorisationId;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            AuthorisationOverview::AGGREGATE_NAME => (string)$this->authorisationOverviewId,
            ApprovableAuthorisationWasCreated::PAYLOAD_AUTHORISATION => (string)$this->authorisationId
        ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->authorisationOverviewId = AuthorisationOverviewId::fromString($payload[AuthorisationOverview::AGGREGATE_NAME]);
        $this->authorisationId = AuthorisationId::fromString($payload[ApprovableAuthorisationWasCreated::PAYLOAD_AUTHORISATION]);
    }
}