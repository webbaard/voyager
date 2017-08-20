<?php

namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Requester\RequesterId;
use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

class RequestAuthorisation extends Command
{
    /**
     * @var AuthorisationId
     */
    private $authorisationId;

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
     * RequestAuthorisation constructor.
     * @param RequesterId $requesterId
     * @param Supplier $supplier
     * @param CostCenter $costCenter
     * @param Commodity $commodity
     * @param ProjectId $projectId
     * @param Amount $amount
     * @param DueDate $dueDate
     * @param Description $description
     */
    private function __construct(RequesterId $requesterId, Supplier $supplier, CostCenter $costCenter, Commodity $commodity, ProjectId $projectId, Amount $amount, DueDate $dueDate, Description $description)
    {
        $this->init();
        $this->authorisationId = AuthorisationId::fromString(Uuid::uuid4());
        $this->requesterId = $requesterId;
        $this->supplier = $supplier;
        $this->costCenter = $costCenter;
        $this->commodity = $commodity;
        $this->projectId = $projectId;
        $this->amount = $amount;
        $this->dueDate = $dueDate;
        $this->description = $description;
    }

    /**
     * @param RequesterId $requesterId
     * @param Supplier $supplier
     * @param CostCenter $costCenter
     * @param Commodity $commodity
     * @param ProjectId $projectId
     * @param Amount $amount
     * @param DueDate $dueDate
     * @param Description $description
     * @return RequestAuthorisation
     */
    public static function from(RequesterId $requesterId, Supplier $supplier, CostCenter $costCenter, Commodity $commodity, ProjectId $projectId, Amount $amount, DueDate $dueDate, Description $description)
    {
        return new self($requesterId, $supplier, $costCenter, $commodity, $projectId, $amount, $dueDate, $description);
    }

    /**
     * @return AuthorisationId
     */
    public function authorisation()
    {
        return $this->authorisationId;
    }

    /**
     * @return RequesterId
     */
    public function requester()
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
     * @return Description
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function payload()
    {
        return [
            AuthorisationRequested::PAYLOAD_AUTHORISATION => $this->authorisation(),
            AuthorisationRequested::PAYLOAD_REQUESTER => $this->requester(),
            AuthorisationRequested::PAYLOAD_SUPPLIER => (string)$this->supplier(),
            AuthorisationRequested::PAYLOAD_COST_CENTER => (string)$this->costCenter(),
            AuthorisationRequested::PAYLOAD_COMMODITY => (string)$this->commodity(),
            AuthorisationRequested::PAYLOAD_PROJECT_ID => (string)$this->projectId(),
            AuthorisationRequested::PAYLOAD_AMOUNT => (int)$this->amount(),
            AuthorisationRequested::PAYLOAD_DUE_DATE => (string)$this->dueDate(),
            AuthorisationRequested::PAYLOAD_DESCRIPTION => $this->description()
        ];
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload)
    {
        $this->authorisationId = AuthorisationId::fromString($payload[AuthorisationRequested::PAYLOAD_AUTHORISATION]);
        $this->requesterId = RequesterId::fromString($payload[AuthorisationRequested::PAYLOAD_REQUESTER]);
        $this->supplier = Supplier::fromString($payload[AuthorisationRequested::PAYLOAD_SUPPLIER]);
        $this->supplier = CostCenter::fromString($payload[AuthorisationRequested::PAYLOAD_COST_CENTER]);
        $this->supplier = Commodity::fromString($payload[AuthorisationRequested::PAYLOAD_COMMODITY]);
        $this->supplier = ProjectId::fromString($payload[AuthorisationRequested::PAYLOAD_PROJECT_ID]);
        $this->amount = Amount::fromString($payload[AuthorisationRequested::PAYLOAD_AMOUNT]);
        $this->amount = DueDate::fromString($payload[AuthorisationRequested::PAYLOAD_DUE_DATE]);
        $this->description = Description::fromString($payload[AuthorisationRequested::PAYLOAD_DESCRIPTION]);
    }
}