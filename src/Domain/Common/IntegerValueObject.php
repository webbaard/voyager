<?php

namespace Printdeal\Voyager\Domain\Common;

trait IntegerValueObject
{
    /**
     * @var int
     */
    private $int;

    /**
     * intValueObject constructor.
     * @param int $int
     */
    private function __construct(int $int)
    {
        $this->int = $int;
    }

    /**
     * @param string $string
     * @return static
     * @internal param $int
     */
    public static function fromString(string $string)
    {
        return new self($string);
    }

    /**
     * @return int
     */
    public function __toString()
    {
        return (string)$this->int;
    }
}