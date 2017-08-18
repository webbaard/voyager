<?php


namespace Printdeal\Voyager\Domain\Requester;


use Printdeal\Voyager\Domain\Common\IdValueObject;
use Printdeal\Voyager\Domain\Signer\SignerId;

class RequesterId implements SignerId
{
    use IdValueObject;
}