<?php


namespace Printdeal\Voyager\Domain\Invoice;


use Printdeal\Voyager\Domain\Common\IdValueObject;
use Printdeal\Voyager\Domain\Subject\SubjectId;

class InvoiceId implements SubjectId
{
    use IdValueObject;
}