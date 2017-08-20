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
     * @param string $date
     * @return self
     */
    public static function fromDateString(string $date)
    {
        return new self(\DateTimeImmutable::createFromFormat('Y-m-d', $date));
    }

    /**
     * @param string $dateTime
     * @return self
     */
    public static function fromString(string $dateTime)
    {
        return new self(\DateTimeImmutable::createFromFormat('U', $dateTime));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->dateTime->getTimestamp();
    }
}