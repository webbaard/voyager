<?php


namespace Printdeal\Voyager\Config;


use Doctrine\DBAL\Driver\Connection;
use Printdeal\Voyager\Domain\Authorisation\AddSubject;
use Printdeal\Voyager\Domain\Authorisation\ApproveAuthorisation;
use Printdeal\Voyager\Domain\Authorisation\Authorisation;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Printdeal\Voyager\Domain\Authorisation\RejectAuthorisation;
use Printdeal\Voyager\Domain\Authorisation\RequestAuthorisation;
use Printdeal\Voyager\Domain\Reviewer\ReviewerId;
use Printdeal\Voyager\Infrastructure\Service\SlackMessager;
use Printdeal\Voyager\Infrastructure\Service\SlackService;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Rhumsaa\Uuid\Uuid;

trait AuthorisationRouterProviding
{
    private function setAuthorisationRouting(CommandRouter $commandRouter, SlackMessager $messager, AuthorisationRepository $repository)
    {
        $this->requestAuthorisation($commandRouter, $repository);
        $this->addSubject($commandRouter, $repository);
        $this->approve($commandRouter, $messager, $repository);
        $this->reject($commandRouter, $messager, $repository);
    }

    private function requestAuthorisation(CommandRouter $commandRouter, AuthorisationRepository $repository)
    {
        $commandRouter
            ->route(RequestAuthorisation::class)
            ->to(function (RequestAuthorisation $command) use ($repository){
                $authorisation = Authorisation::request(
                    $command->authorisation(),
                    $command->requester(),
                    $command->supplier(),
                    $command->costCenter(),
                    $command->commodity(),
                    $command->projectId(),
                    $command->amount(),
                    $command->dueDate(),
                    $command->description()
                );

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

    private function approve(CommandRouter $commandRouter, SlackMessager $messager, AuthorisationRepository $repository)
    {
        $commandRouter
            ->route(ApproveAuthorisation::class)
            ->to(function (ApproveAuthorisation $command) use ($repository, $messager){
                $authorisation = $repository->get(Uuid::fromString($command->authorisation()));
                $authorisation->approve($command->reviewer());

                $messager->sendMessage($authorisation->requester(), 'Your Authorisation has been Approved. see more at http://127.0.0.1:8080/authorisation/' . $authorisation->id());

                $repository->save($authorisation);
            });
    }

    private function reject(CommandRouter $commandRouter, SlackMessager $messager, AuthorisationRepository $repository)
    {
        $commandRouter
            ->route(RejectAuthorisation::class)
            ->to(function (RejectAuthorisation $command) use ($repository, $messager){
                $authorisation = $repository->get(Uuid::fromString($command->authorisation()));

                $messager->sendMessage($authorisation->requester(), 'Your Authorisation has been Rejected. see more at http://127.0.0.1:8080/authorisation/' . $authorisation->id());

                $authorisation->reject($command->reviewer());
                $repository->save($authorisation);
            });
    }
}