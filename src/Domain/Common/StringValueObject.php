<?php

namespace Printdeal\Voyager\Domain\Common;

trait StringValueObject
{
    /**
     * @var string
     */
    private $string;

    /**
     * stringValueObject constructor.
     * @param string $string
     */
    private function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * @param $string
     * @return self
     */
    public static function fromString(string $string)
    {
        return new self($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->string;
    }
}