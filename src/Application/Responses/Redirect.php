<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\Responses;

use IceHawk\IceHawk\Constants\HttpCode;

/**
 * Class Redirect
 * @package Printdeal\Voyager\Application\Responses
 */
final class Redirect
{
	/** @var string */
	private $url;

	/** @var int */
	private $httpCode;

	public function __construct( string $url, int $httpCode = HttpCode::MOVED_PERMANENTLY )
	{
		$this->url      = $url;
		$this->httpCode = $httpCode;
	}

	public function respond()
	{
		header( 'Content-Type: text/html; charset=utf8', true, $this->httpCode );
		header( 'Location: ' . $this->url );

		echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="3; URL=' . $this->url . '"></head><body>';
		echo 'Redirecting to <a href="' . $this->url . '">' . $this->url . '</a>';
		echo '</body></html>';
		flush();
	}
}
