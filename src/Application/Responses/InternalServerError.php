<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\Responses;

use IceHawk\IceHawk\Constants\HttpCode;

/**
 * Class InternalServerError
 * @package Printdeal\Voyager\Application\Responses
 */
final class InternalServerError
{
	public function respond()
	{
		header( 'Content-Type: text/html; charset=utf-8', true, HttpCode::INTERNAL_SERVER_ERROR );
		echo "500 - Internal Server Error";
		flush();
	}
}
