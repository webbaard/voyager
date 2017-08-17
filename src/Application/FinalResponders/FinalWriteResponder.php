<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\FinalResponders;

use Printdeal\Voyager\Application\Responses\InternalServerError;
use Printdeal\Voyager\Application\Responses\NotFound;
use IceHawk\IceHawk\Exceptions\UnresolvedRequest;
use IceHawk\IceHawk\Interfaces\ProvidesWriteRequestData;
use IceHawk\IceHawk\Interfaces\RespondsFinallyToWriteRequest;

/**
 * Class FinalWriteResponder
 * @package Printdeal\Voyager\Application\FinalResponders
 */
final class FinalWriteResponder implements RespondsFinallyToWriteRequest
{
	public function handleUncaughtException( \Throwable $throwable, ProvidesWriteRequestData $request )
	{
		try
		{
			throw $throwable;
		}
		catch ( UnresolvedRequest $e )
		{
			# No matching route was found, respond with a 404 Not Found

			(new NotFound())->respond();
		}
		catch ( \Throwable $e )
		{
			# Something else went wrong, respond with a 500 Internal Server Error

			(new InternalServerError())->respond();
		}
	}
}
