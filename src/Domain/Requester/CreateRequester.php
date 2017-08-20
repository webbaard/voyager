<?php


namespace Printdeal\Voyager\Domain\Requester;


use Printdeal\Voyager\Domain\Employee\EmployeeName;
use Prooph\Common\Messaging\Command;

class CreateRequester extends Command
{
    private $requesterId;

    private $employeeName;

    private function __construct(RequesterId $requesterId, EmployeeName $employeeName)
    {
        $this->init();
        $this->requesterId = $requesterId;
        $this->employeeName = $employeeName;
    }

    public static function from(RequesterId $requesterId, EmployeeName $employeeName)
    {
        return new self($requesterId, $employeeName);
    }

    public function requester(): RequesterId
    {
        return $this->requesterId;
    }

    public function employeeName(): EmployeeName
    {
        return $this->employeeName;
    }

    public function payload()
    {
        return [
            RequesterCreated::PAYLOAD_REQUESTER => $this->requester(),
            RequesterCreated::PAYLOAD_EMPLOYEE => $this->employeeName()
        ];
    }

    public function setPayload(array $payload)
    {
        $this->requesterId = RequesterId::fromString($payload[RequesterCreated::PAYLOAD_REQUESTER]);
        $this->employeeName = EmployeeName::fromString($payload[RequesterCreated::PAYLOAD_EMPLOYEE]);
    }
}