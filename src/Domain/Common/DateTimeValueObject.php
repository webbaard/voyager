<?php

namespace Printdeal\Voyager\Domain\Common;

trait DateTimeValueObject
{
    /**
     * @var \DateTimeInterface
     */
    private $dateTime;

    /**
     * DateTimeValueObject constructor.
     * @param \DateTimeInterface $dateTime
     */
    private function __construct(\DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @param $dateTime
     * @return self
     */
    public static function fromString(string $dateTime)
    {
        return new self(new \DateTimeImmutable($dateTime));
    }

    /**
     * @return string
     */
    public function __toStringTime()
    {
        return (string)$this->dateTime;
    }
}