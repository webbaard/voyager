<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\Endpoints\Start\Read;

use Printdeal\Voyager\Application\Responses\Page;
use IceHawk\IceHawk\Interfaces\HandlesGetRequest;
use IceHawk\IceHawk\Interfaces\ProvidesReadRequestData;

/**
 * Class ListRequestHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Read
 */
final class ListRequestHandler implements HandlesGetRequest
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle( ProvidesReadRequestData $request )
	{
        $template = $this->twig->load('Request/list.html.twig');

		echo $template->render(
		    [
		        'test' => 'hallo'
            ]
        );
	}
}
