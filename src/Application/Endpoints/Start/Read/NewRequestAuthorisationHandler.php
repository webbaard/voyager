<?php

namespace Printdeal\Voyager\Application\Endpoints\Start\Read;

use Doctrine\DBAL\Driver\Connection;
use IceHawk\IceHawk\Interfaces\HandlesGetRequest;
use IceHawk\IceHawk\Interfaces\ProvidesReadRequestData;
use Printdeal\Voyager\Application\Infra\SecurityService;
use Printdeal\Voyager\Domain\AuthorisationOverview\CreateAuthorisationOverview;
use Prooph\ServiceBus\CommandBus;


/**
 * Class NewRequestAuthorisationHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Read
 */
final class NewRequestAuthorisationHandler implements HandlesGetRequest
{
    /** @var SecurityService */
    private $securityService;

    /** @var Connection */
    private $databaseConnection;

    /** @var CommandBus */
    private $commandBus;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * NewRequestAuthorisationHandler constructor.
     * @param SecurityService $securityService
     * @param Connection $databaseConnection
     * @param CommandBus $commandBus
     * @param \Twig_Environment $twig
     */
    public function __construct(SecurityService $securityService, Connection $databaseConnection, CommandBus $commandBus, \Twig_Environment $twig)
    {
        $this->securityService = $securityService;
        $this->databaseConnection = $databaseConnection;
        $this->commandBus = $commandBus;
        $this->twig = $twig;
    }

    public function handle( ProvidesReadRequestData $request )
    {
        $userName = $this->securityService->getUserInfo()->getEmail();
        $userReference = $this->databaseConnection->query('
	        SELECT id 
            FROM user_overview 
            WHERE user_name = "' . $userName . '"
        ')->fetch();
        if (!$userReference) {
            $overviewCommand = CreateAuthorisationOverview::from();
            $this->commandBus->dispatch($overviewCommand);
            $userReference['id'] = $overviewCommand->authorisationOverview();
            $this->databaseConnection->query('
                INSERT INTO user_overview
                (id, user_name)
                VALUES 
                ("' . $overviewCommand->authorisationOverview() .'","'. $userName . '")
            ');
        }

        $template = $this->twig->load('Request/add.html.twig');

        echo $template->render(
            [
                'userReference' => $userReference['id']
            ]
        );
    }
}