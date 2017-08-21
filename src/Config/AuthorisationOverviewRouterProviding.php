<?php

namespace Printdeal\Voyager\Config;

use Printdeal\Voyager\Domain\Authorisation\AddSubject;
use Printdeal\Voyager\Domain\Authorisation\Authorisation;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Printdeal\Voyager\Domain\Authorisation\RequestAuthorisation;
use Printdeal\Voyager\Domain\AuthorisationOverview\AddApprovableAuthorisation;
use Printdeal\Voyager\Domain\AuthorisationOverview\AddRequestedAuthorisation;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverview;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverviewRepository;
use Printdeal\Voyager\Domain\AuthorisationOverview\CreateAuthorisationOverview;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Rhumsaa\Uuid\Uuid;

trait AuthorisationOverviewRouterProviding
{
    private function setAuthorisationOverviewRouting(CommandRouter $commandRouter, AuthorisationOverviewRepository $repository)
    {
        $this->createAuthorisationOverview($commandRouter, $repository);
        $this->addRequestedAuthorisation($commandRouter, $repository);
        $this->addApprovableAuthorisation($commandRouter, $repository);
    }

    private function createAuthorisationOverview(CommandRouter $commandRouter, AuthorisationOverviewRepository $repository)
    {
        $commandRouter
            ->route(CreateAuthorisationOverview::class)
            ->to(function (CreateAuthorisationOverview $command) use ($repository){
                $authorisation = AuthorisationOverview::create($command->authorisationOverview(), $command->owner());

                $repository->save($authorisation);
            });
    }

    private function addRequestedAuthorisation(CommandRouter $commandRouter, AuthorisationOverviewRepository $repository)
    {
        $commandRouter
            ->route(AddRequestedAuthorisation::class)
            ->to(function(AddRequestedAuthorisation $command) use ($repository){
                $authorisationOverview = $repository->get(Uuid::fromString($command->authorisationOverview()));
                $authorisationOverview->addRequestedAuthorisation($command->authorisation());
                $repository->save($authorisationOverview);
            });
    }

    private function addApprovableAuthorisation(CommandRouter $commandRouter, AuthorisationOverviewRepository $repository)
    {
        $commandRouter
            ->route(AddApprovableAuthorisation::class)
            ->to(function(AddApprovableAuthorisation $command) use ($repository){
                $authorisationOverview = $repository->get(Uuid::fromString($command->authorisationOverview()));
                $authorisationOverview->addApprovableAuthorisation($command->authorisation());
                $repository->save($authorisationOverview);
            });
    }
}