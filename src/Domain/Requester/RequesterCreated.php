<?php


namespace Printdeal\Voyager\Domain\Requester;


use Printdeal\Voyager\Domain\Employee\EmployeeName;
use Prooph\EventSourcing\AggregateChanged;

class RequesterCreated extends AggregateChanged
{
    const PAYLOAD_REQUESTER = 'requester';
    const PAYLOAD_EMPLOYEE = 'employee';

    /**
     * @param RequesterId $id
     * @param EmployeeName $employeeName
     * @return static
     */
    public static function from(RequesterId $id, EmployeeName $employeeName)
    {
        return self::occur(
            (string)$id,
            [
                self::PAYLOAD_REQUESTER => (string)$id,
                self::PAYLOAD_EMPLOYEE => (string)$employeeName
            ]
        );
    }

    /**
     * @return RequesterId
     */
    public function requester(): RequesterId
    {
        return RequesterId::fromString($this->payload[self::PAYLOAD_REQUESTER]);
    }

    /**
     * @return EmployeeName
     */
    public function employee(): EmployeeName
    {
        return EmployeeName::fromString($this->payload[self::PAYLOAD_EMPLOYEE]);
    }
}