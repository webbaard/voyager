<?php

namespace Printdeal\Voyager\Domain\Common;

use Rhumsaa\Uuid\Uuid;

trait IdValueObject
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * IdValueObject constructor.
     * @param Uuid $id
     */
    private function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @param $id
     * @return self
     */
    public static function fromString($id)
    {
        return new self(Uuid::fromString($id));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }
}