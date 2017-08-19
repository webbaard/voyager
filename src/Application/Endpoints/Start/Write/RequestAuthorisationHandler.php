<?php


namespace Printdeal\Voyager\Application\Endpoints\Start\Write;
use IceHawk\IceHawk\Interfaces\HandlesPostRequest;
use IceHawk\IceHawk\Interfaces\ProvidesWriteRequestData;
use Printdeal\Voyager\Application\Responses\Redirect;
use Printdeal\Voyager\Domain\Authorisation\AddSubject;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Printdeal\Voyager\Domain\Authorisation\Description;
use Printdeal\Voyager\Domain\Authorisation\RequestAuthorisation;
use Printdeal\Voyager\Domain\Invoice\InvoiceId;
use Printdeal\Voyager\Domain\Requester\RequesterId;
use Printdeal\Voyager\Domain\Subject\SubjectId;
use Prooph\ServiceBus\CommandBus;
use Rhumsaa\Uuid\Uuid;


/**
 * Class DoSomethingRequestHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Write
 */
final class RequestAuthorisationHandler implements HandlesPostRequest
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * RequestAuthorisationHandler constructor.
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle( ProvidesWriteRequestData $request )
    {
        $command = RequestAuthorisation::from(
            RequesterId::fromString(Uuid::uuid4()),
            Description::fromString('test')
        );
        $this->commandBus->dispatch($command);
        $this->commandBus->dispatch(AddSubject::from(
            AuthorisationId::fromString($command->authorisation()),
            InvoiceId::fromString(Uuid::uuid4())
        ));

        (new Redirect( "/" ))->respond();
    }
}