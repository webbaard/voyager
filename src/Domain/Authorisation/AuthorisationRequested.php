<?php


namespace Printdeal\Voyager\Domain\Authorisation;

use Printdeal\Voyager\Domain\Requester\RequesterId;
use Prooph\EventSourcing\AggregateChanged;
use Rhumsaa\Uuid\Uuid;

class AuthorisationRequested extends AggregateChanged
{
    const PAYLOAD_AUTHORISATION = 'authorisation';
    const PAYLOAD_REQUESTER = 'requester';
    const PAYLOAD_SUPPLIER = 'supplier';
    const PAYLOAD_COST_CENTER = 'cost-center';
    const PAYLOAD_COMMODITY = 'commodity';
    const PAYLOAD_PROJECT_ID = 'project-id';
    const PAYLOAD_AMOUNT = 'amount';
    const PAYLOAD_DUE_DATE = 'due-date';
    const PAYLOAD_DESCRIPTION = 'description';

    /**
     * @param AuthorisationId $id
     * @param RequesterId $requesterId
     * @param Supplier $supplier
     * @param CostCenter $costCenter
     * @param Commodity $commodity
     * @param ProjectId $projectId
     * @param Amount $amount
     * @param DueDate $dueDate
     * @param string $description
     * @return AuthorisationRequested
     */
    public static function from(AuthorisationId $id, RequesterId $requesterId, Supplier $supplier, CostCenter $costCenter, Commodity $commodity, ProjectId $projectId, Amount $amount, DueDate $dueDate, string $description)
    {
        return self::occur(
            (string)$id,
            [
                self::PAYLOAD_AUTHORISATION => (string)$id,
                self::PAYLOAD_REQUESTER => (string)$requesterId,
                self::PAYLOAD_SUPPLIER => (string)$supplier,
                self::PAYLOAD_COST_CENTER => (string)$costCenter,
                self::PAYLOAD_COMMODITY => (string)$commodity,
                self::PAYLOAD_PROJECT_ID => (string)$projectId,
                self::PAYLOAD_AMOUNT => (string)$amount,
                self::PAYLOAD_DUE_DATE => (string)$dueDate,
                self::PAYLOAD_DESCRIPTION => $description
            ]
        );
    }

    /**
     * @return AuthorisationId
     */
    public function authorisation(): AuthorisationId
    {
        return AuthorisationId::fromString($this->payload[self::PAYLOAD_AUTHORISATION]);
    }

    /**
     * @return RequesterId
     */
    public function requester(): RequesterId
    {
        return RequesterId::fromString($this->payload[self::PAYLOAD_REQUESTER]);
    }

    /**
     * @return Supplier
     */
    public function supplier(): Supplier
    {
        if(!isset($this->payload[self::PAYLOAD_SUPPLIER])) {
            return Supplier::fromString('');
        }
        return Supplier::fromString($this->payload[self::PAYLOAD_SUPPLIER]);
    }

    /**
     * @return CostCenter
     */
    public function costCenter(): CostCenter
    {
        if(!isset($this->payload[self::PAYLOAD_COST_CENTER])) {
            return CostCenter::fromString('');
        }
        return CostCenter::fromString($this->payload[self::PAYLOAD_COST_CENTER]);
    }
    /**
     * @return Commodity
     */
    public function commodity(): Commodity
    {
        if(!isset($this->payload[self::PAYLOAD_COMMODITY])) {
            return Commodity::fromString('');
        }
        return Commodity::fromString($this->payload[self::PAYLOAD_COMMODITY]);
    }

    /**
     * @return ProjectId
     */
    public function projectId(): ProjectId
    {
        if(!isset($this->payload[self::PAYLOAD_PROJECT_ID])) {
            return ProjectId::fromString(0);
        }
        return ProjectId::fromString($this->payload[self::PAYLOAD_PROJECT_ID]);
    }

    /**
     * @return Amount
     */
    public function amount(): Amount
    {
        if(!isset($this->payload[self::PAYLOAD_AMOUNT])) {
            return Amount::fromString(0);
        }
        return Amount::fromString($this->payload[self::PAYLOAD_AMOUNT]);
    }

    /**
     * @return DueDate
     */
    public function dueDate(): ?DueDate
    {
        if(!isset($this->payload[self::PAYLOAD_DUE_DATE])) {
            return null;
        }
        return DueDate::fromString($this->payload[self::PAYLOAD_DUE_DATE]);
    }

    /**
     * @return Description
     */
    public function description(): Description
    {
        return Description::fromString($this->payload[self::PAYLOAD_DESCRIPTION]);
    }
}