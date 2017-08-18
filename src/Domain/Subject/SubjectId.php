<?php


namespace Printdeal\Voyager\Domain\Subject;


interface SubjectId
{
    /**
     * @param $id
     * @return SubjectId
     */
    public static function fromString($id);

    /**
     * @return string
     */
    public function __toString();
}