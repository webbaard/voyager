<?php

namespace Printdeal\Voyager\Application\Endpoints\Start\Read;

use IceHawk\IceHawk\Interfaces\HandlesGetRequest;
use IceHawk\IceHawk\Interfaces\ProvidesReadRequestData;
use Printdeal\Voyager\Application\Responses\Page;


/**
 * Class NewRequestAuthorisationHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Read
 */
final class NewRequestAuthorisationHandler implements HandlesGetRequest
{
    public function handle( ProvidesReadRequestData $request )
    {
        # This method handles a GET (and HEAD) Request

        # And responds with a 200 OK and page content

        (new Page())->respond( file_get_contents(__DIR__ . '/Pages/Request/add.html.twig') );
    }
}