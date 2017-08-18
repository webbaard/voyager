<?php declare(strict_types = 1);

namespace Printdeal\Voyager\Application\Endpoints\Start\Write;

use Printdeal\Voyager\Application\Responses\Redirect;
use IceHawk\IceHawk\Interfaces\HandlesPostRequest;
use IceHawk\IceHawk\Interfaces\ProvidesWriteRequestData;

/**
 * Class DoSomethingRequestHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Write
 */
final class DoSomethingRequestHandler implements HandlesPostRequest
{
	public function handle( ProvidesWriteRequestData $request )
	{
		# This method handles a post request

		# And responds with a 301 redirect back to /

		(new Redirect( "/" ))->respond();
	}
}
