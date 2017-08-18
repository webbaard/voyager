<?php


namespace Printdeal\Voyager\Domain\Reviewer;


use Printdeal\Voyager\Domain\Common\IdValueObject;
use Printdeal\Voyager\Domain\Signer\SignerId;

class ReviewerId implements SignerId
{
    use IdValueObject;
}