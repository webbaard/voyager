<?php


namespace Printdeal\Voyager\Application\Endpoints\Start\Write;
use IceHawk\IceHawk\Interfaces\HandlesPostRequest;
use IceHawk\IceHawk\Interfaces\ProvidesWriteRequestData;
use Printdeal\Voyager\Application\Responses\Redirect;
use Printdeal\Voyager\Domain\Authorisation\AddSubject;
use Printdeal\Voyager\Domain\Authorisation\Amount;
use Printdeal\Voyager\Domain\Authorisation\ApproveAuthorisation;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationId;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Printdeal\Voyager\Domain\Authorisation\Commodity;
use Printdeal\Voyager\Domain\Authorisation\CostCenter;
use Printdeal\Voyager\Domain\Authorisation\Description;
use Printdeal\Voyager\Domain\Authorisation\DueDate;
use Printdeal\Voyager\Domain\Authorisation\ProjectId;
use Printdeal\Voyager\Domain\Authorisation\RequestAuthorisation;
use Printdeal\Voyager\Domain\Authorisation\Supplier;
use Printdeal\Voyager\Domain\AuthorisationOverview\AddApprovableAuthorisation;
use Printdeal\Voyager\Domain\AuthorisationOverview\AddRequestedAuthorisation;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverviewId;
use Printdeal\Voyager\Domain\AuthorisationOverview\RequestedAuthorisationWasCreated;
use Printdeal\Voyager\Domain\Invoice\InvoiceId;
use Printdeal\Voyager\Domain\Requester\RequesterId;
use Printdeal\Voyager\Domain\Reviewer\ReviewerId;
use Printdeal\Voyager\Domain\Subject\SubjectId;
use Prooph\ServiceBus\CommandBus;
use Rhumsaa\Uuid\Uuid;


/**
 * Class DoSomethingRequestHandler
 * @package Printdeal\Voyager\Application\Endpoints\Start\Write
 */
final class ApproveAuthorisationHandler implements HandlesPostRequest
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var AuthorisationRepository
     */
    private $authorisationRepository;

    /**
     * RequestAuthorisationHandler constructor.
     * @param CommandBus $commandBus
     * @param AuthorisationRepository $repository
     */
    public function __construct(CommandBus $commandBus, AuthorisationRepository $repository)
    {
        $this->commandBus = $commandBus;
        $this->authorisationRepository = $repository;
    }

    public function handle( ProvidesWriteRequestData $request )
    {
        $authorisation = $this->authorisationRepository->get(Uuid::fromString($request->getInput()->get('authorisation')));

        $this->commandBus->dispatch(ApproveAuthorisation::from(
            AuthorisationId::fromString($authorisation->id()),
            ReviewerId::fromString(Uuid::uuid4())
        ));
        $this->commandBus->dispatch(AddApprovableAuthorisation::from(
            AuthorisationOverviewId::fromString($request->getInput()->get('user_reference')),
            AuthorisationId::fromString($authorisation->id())
        ));

        (new Redirect( "/" ))->respond();
    }
}