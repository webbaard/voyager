<?php


namespace Printdeal\Voyager\Application\Endpoints\Start\Write;
use IceHawk\IceHawk\Interfaces\HandlesPostRequest;
use IceHawk\IceHawk\Interfaces\ProvidesWriteRequestData;
use Printdeal\Voyager\Application\Responses\Redirect;
use Printdeal\Voyager\Domain\Authorisation\AddSubject;
use Printdeal\Voyager\Domain\Authorisation\Amount;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Printdeal\Voyager\Domain\Authorisation\Commodity;
use Printdeal\Voyager\Domain\Authorisation\CostCenter;
use Printdeal\Voyager\Domain\Authorisation\Description;
use Printdeal\Voyager\Domain\Authorisation\DueDate;
use Printdeal\Voyager\Domain\Authorisation\ProjectId;
use Printdeal\Voyager\Domain\Authorisation\RequestAuthorisation;
use Printdeal\Voyager\Domain\Authorisation\Supplier;
use Printdeal\Voyager\Domain\AuthorisationOverview\AddRequestedAuthorisation;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverviewId;
use Printdeal\Voyager\Domain\AuthorisationOverview\RequestedAuthorisationWasCreated;
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
        $authorisationCommand = RequestAuthorisation::from(
            RequesterId::fromString($request->getInput()->get('user_reference')),
            Supplier::fromString($request->getInput()->get('supplier')),
            CostCenter::fromString($request->getInput()->get('cost_center')),
            Commodity::fromString($request->getInput()->get('commodity')),
            ProjectId::fromString($request->getInput()->get('project_id')),
            Amount::fromString($request->getInput()->get('amount')),
            DueDate::fromDateString($request->getInput()->get('due_date')),
            Description::fromString($request->getInput()->get('description'))
        );
        $this->commandBus->dispatch($authorisationCommand);
        $this->commandBus->dispatch(AddSubject::from(
            AuthorisationId::fromString($authorisationCommand->authorisation()),
            InvoiceId::fromString(Uuid::uuid4())
        ));
        $this->commandBus->dispatch(AddRequestedAuthorisation::from(
            AuthorisationOverviewId::fromString($request->getInput()->get('user_reference')),
            AuthorisationId::fromString($authorisationCommand->authorisation())
        ));

        (new Redirect( "/" ))->respond();
    }
}