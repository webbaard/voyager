<?php declare(strict_types = 1);

namespace Printdeal\Voyager\Application\Endpoints\Start\Read;

use Printdeal\Voyager\Application\Responses\Page;
use IceHawk\IceHawk\Interfaces\HandlesGetRequest;
use IceHawk\IceHawk\Interfaces\ProvidesReadRequestData;
use Printdeal\Voyager\Application\Responses\Redirect;

/**
 * Class SayHelloRequestHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Read
 */
final class LoginRequestHandler implements HandlesGetRequest
{
    public function handle( ProvidesReadRequestData $request )
    {
        (new Redirect('/'))->respond();
    }
}
