<?php


namespace Printdeal\Voyager\Config;


use Printdeal\Voyager\Domain\Authorisation\AddSubject;
use Printdeal\Voyager\Domain\Authorisation\Authorisation;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Printdeal\Voyager\Domain\Authorisation\RequestAuthorisation;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Rhumsaa\Uuid\Uuid;

trait AuthorisationRouterProviding
{
    private function setAuthorisationRouting(CommandRouter $commandRouter, AuthorisationRepository $repository)
    {
        $this->requestAuthorisation($commandRouter, $repository);
        $this->addSubject($commandRouter, $repository);
    }

    private function requestAuthorisation(CommandRouter $commandRouter, AuthorisationRepository $repository)
    {
        $commandRouter
            ->route(RequestAuthorisation::class)
            ->to(function (RequestAuthorisation $command) use ($repository){
                $authorisation = Authorisation::request($command->authorisation(), $command->requester(), $command->description());

                $repository->save($authorisation);
            });
    }

    private function addSubject(CommandRouter $commandRouter, AuthorisationRepository $repository)
    {
        $commandRouter
            ->route(AddSubject::class)
            ->to(function (AddSubject $command) use ($repository){
                $authorisation = $repository->get(Uuid::fromString($command->authorisation()));
                $authorisation->addSubject($command->subject());
                $repository->save($authorisation);
            });
    }
}