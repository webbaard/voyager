<?php


namespace Printdeal\Voyager\Domain\Signer;


interface SignerId
{
    /**
     * @param $id
     * @return SignerId
     */
    public static function fromString($id);

    /**
     * @return string
     */
    public function __toString();
}