<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\Responses;

use IceHawk\IceHawk\Constants\HttpCode;

/**
 * Class Page
 * @package Printdeal\Voyager\Application\Responses
 */
final class Page
{
	public function respond( string $content, int $httpCode = HttpCode::OK )
	{
		header( 'Content-Type: text/html; charset=utf-8', true, $httpCode );

		# Implement you layout rendering here

		echo $content;
		flush();
	}
}
