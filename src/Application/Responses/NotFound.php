<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\Responses;

use IceHawk\IceHawk\Constants\HttpCode;

/**
 * Class NotFound
 * @package Printdeal\Voyager\Application\Responses
 */
final class NotFound
{
	public function respond()
	{
		header( 'Content-Type: text/plain; charset=utf-8', true, HttpCode::NOT_FOUND );
		echo "404 - Not found";
		flush();
	}
}
