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
final class ListRequestHandler implements HandlesGetRequest
{
    private $twig;

    private $authorisationOverviewRepository;

    private $authorisationRepository;

    private $userReference;

    public function __construct(
        \Twig_Environment $twig,
        AuthorisationOverviewRepository $authorisationOverviewRepository,
        AuthorisationRepository $authorisationRepository,
        string $userReference
    ) {
        $this->twig = $twig;
        $this->authorisationOverviewRepository = $authorisationOverviewRepository;
        $this->authorisationRepository = $authorisationRepository;
        $this->userReference = $userReference;
    }

    public function handle( ProvidesReadRequestData $request )
	{
        $overview = [
            'requested' =>[],
            'approvable' => []
        ];
	    $overviewEvent = $this->authorisationOverviewRepository->get(Uuid::fromString($this->userReference));

	    foreach ($overviewEvent->requestedAuthorisations() as $authorisation) {
	        $overview['requested'][] = $this->authorisationRepository->get(Uuid::fromString($authorisation));
        }

        foreach ($overviewEvent->approvableAuthorisations() as $authorisation) {
	        $overview['approvable'][] = $this->authorisationRepository->get(Uuid::fromString($authorisation));
         }

        $template = $this->twig->load('Request/list.html.twig');

		echo $template->render(
		    [
		        'overview' => $overview
            ]
        );
	}
}
