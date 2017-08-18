<?php


namespace Printdeal\Voyager\Application\Endpoints\Start\Write;
use IceHawk\IceHawk\Interfaces\HandlesPostRequest;
use IceHawk\IceHawk\Interfaces\ProvidesWriteRequestData;
use Printdeal\Voyager\Application\Responses\Redirect;


/**
 * Class DoSomethingRequestHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Write
 */
final class RequestAuthorisationHandler implements HandlesPostRequest
{
    public function handle( ProvidesWriteRequestData $request )
    {
        # This method handles a post request

        # And responds with a 301 redirect back to /

        (new Redirect( "/" ))->respond();
    }
}