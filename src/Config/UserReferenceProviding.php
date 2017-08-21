<?php


namespace Printdeal\Voyager\Config;


use Doctrine\DBAL\Driver\Connection;
use Printdeal\Voyager\Application\Infra\SecurityService;
use Printdeal\Voyager\Domain\AuthorisationOverview\CreateAuthorisationOverview;
use Printdeal\Voyager\Domain\AuthorisationOverview\Owner;
use Prooph\ServiceBus\CommandBus;

trait UserReferenceProviding
{
    /**
     * @param SecurityService $securityService
     * @param Connection $connection
     * @param CommandBus $commandBus
     * @return string
     */
    private function getUserReference(SecurityService $securityService, Connection $connection, CommandBus $commandBus)
    {
        $userName = $securityService->getUserInfo()->getEmail();
        $userReference = $connection->query('
	        SELECT id 
            FROM user_overview 
            WHERE user_name = "' . $userName . '"
        ')->fetch();
        if (!$userReference) {
            $overviewCommand = CreateAuthorisationOverview::from(Owner::fromString($userName));
            $commandBus->dispatch($overviewCommand);
            $userReference['id'] = (string)$overviewCommand->authorisationOverview();
            $connection->query('
                INSERT INTO user_overview
                (id, user_name)
                VALUES 
                ("' . $overviewCommand->authorisationOverview() .'","'. $userName . '")
            ');
        }
        return $userReference['id'];
    }
}