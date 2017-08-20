<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\Endpoints\Start\Read;

use Doctrine\DBAL\Driver\Connection;
use Printdeal\Voyager\Application\Infra\SecurityService;
use Printdeal\Voyager\Application\Responses\Page;
use IceHawk\IceHawk\Interfaces\HandlesGetRequest;
use IceHawk\IceHawk\Interfaces\ProvidesReadRequestData;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverview;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverviewRepository;
use Printdeal\Voyager\Domain\AuthorisationOverview\CreateAuthorisationOverview;
use Printdeal\Voyager\Domain\AuthorisationOverview\Owner;
use Prooph\ServiceBus\CommandBus;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ListRequestHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Read
 */
final class DetailAuthorisationHandler implements HandlesGetRequest
{
    private $twig;

    private $authorisationRepository;

    private $userReference;

    public function __construct(
        \Twig_Environment $twig,
        AuthorisationRepository $authorisationRepository,
        string $userReference
    ) {
        $this->twig = $twig;
        $this->authorisationRepository = $authorisationRepository;
        $this->userReference = $userReference;
    }

    public function handle( ProvidesReadRequestData $request )
	{
        $template = $this->twig->load('Request/detail.html.twig');
		echo $template->render(
		    [
		        'userReference' => $this->userReference,
		        'authorisation' => $this->authorisationRepository->get(Uuid::fromString($request->getInput()->get('authorisationId')))
            ]
        );
	}
}
