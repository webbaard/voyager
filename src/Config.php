<?php declare(strict_types = 1);

namespace Printdeal\Voyager;

use Doctrine\DBAL\Connection;
use IceHawk\IceHawk\IceHawk;
use IceHawk\IceHawk\Interfaces\ProvidesCookieData;
use IceHawk\IceHawk\Routing\Interfaces\BypassesRequest;
use IceHawk\IceHawk\Defaults\Cookies;
use IceHawk\IceHawk\Routing\Patterns\RegExp;
use Printdeal\Voyager\Application\Endpoints\Start\Read\DetailAuthorisationHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Read\ListRequestHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Read\LoginRequestHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Read\NewRequestAuthorisationHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Write\ApproveAuthorisationHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Write\RejectAuthorisationHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Write\RequestAuthorisationHandler;
use Printdeal\Voyager\Application\EventSubscribers\IceHawkInitEventSubscriber;
use Printdeal\Voyager\Application\EventSubscribers\IceHawkReadEventSubscriber;
use Printdeal\Voyager\Application\EventSubscribers\IceHawkWriteEventSubscriber;
use Printdeal\Voyager\Application\FinalResponders\FinalReadResponder;
use Printdeal\Voyager\Application\FinalResponders\FinalWriteResponder;
use IceHawk\IceHawk\Interfaces\RespondsFinallyToReadRequest;
use IceHawk\IceHawk\Interfaces\RespondsFinallyToWriteRequest;
use IceHawk\IceHawk\Routing\Patterns\Literal;
use IceHawk\IceHawk\Routing\ReadRoute;
use IceHawk\IceHawk\Routing\WriteRoute;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\BeanFactoryRegistry;
use IceHawk\IceHawk\Defaults\RequestInfo;
use IceHawk\IceHawk\Interfaces\ProvidesRequestInfo;
use Printdeal\Voyager\Config\AuthorisationOverviewRepositoryProviding;
use Printdeal\Voyager\Config\AuthorisationOverviewRouterProviding;
use Printdeal\Voyager\Config\AuthorisationRepositoryProviding;
use Printdeal\Voyager\Config\AuthorisationRouterProviding;
use Printdeal\Voyager\Config\CommandBusProviding;
use Printdeal\Voyager\Config\CommandRouterProviding;
use Printdeal\Voyager\Config\DatabaseProviding;
use Printdeal\Voyager\Config\EventRouterProviding;
use Printdeal\Voyager\Config\EventStoreProviding;
use Printdeal\Voyager\Config\MessageProviding;
use Printdeal\Voyager\Config\SlackProviding;
use Printdeal\Voyager\Config\TwigProviding;
use Printdeal\Voyager\Config\UserReferenceProviding;
use Printdeal\Voyager\Domain\Authorisation\AuthorisationRepository;
use Printdeal\Voyager\Domain\AuthorisationOverview\AuthorisationOverviewRepository;
use Printdeal\Voyager\Infrastructure\Service\SlackMessager;
use Printdeal\Voyager\Infrastructure\Service\SlackService;
use Prooph\EventStore\EventStore;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Prooph\ServiceBus\Plugin\Router\EventRouter;
use Printdeal\Voyager\Application\Infra\SecurityService;

/**
 * @Configuration
 */
class Config
{
    use DatabaseProviding;
    use EventStoreProviding;
    use EventRouterProviding;
    use CommandBusProviding;
    use CommandRouterProviding;
    use SlackProviding;
    use TwigProviding;
    use UserReferenceProviding;
    use MessageProviding;

    use AuthorisationRouterProviding;
    use AuthorisationOverviewRouterProviding;

    use AuthorisationRepositoryProviding;
    use AuthorisationOverviewRepositoryProviding;

    /**
     * @Bean
     * @return ProvidesRequestInfo
     */
	public function requestInfo(): ProvidesRequestInfo
    {
        return RequestInfo::fromEnv();
    }

    private function setCommandRouting()
    {
        $this->setAuthorisationRouting($this->commandRouter(), $this->slackMessager(), $this->authorisationRepository());
        $this->setAuthorisationOverviewRouting($this->commandRouter(), $this->authorisationOverviewRepository());
    }


    /**
     * @Bean
     * @return array
     */
	public function readRoutes(): array
	{
	    $this->setCommandRouting();
        return [
            new ReadRoute( new Literal( '/auth0/callback'), new LoginRequestHandler() ),
            new ReadRoute( new Literal( '/' ), new ListRequestHandler(
			    $this->twig(),
                $this->authorisationOverviewRepository(),
                $this->authorisationRepository(),
                $this->userReference()
            ) ),
            new ReadRoute( new Literal( '/authorisation/create'), new NewRequestAuthorisationHandler(
                $this->securityService(),
                $this->databaseConnection(),
                $this->commandBus(),
                $this->twig()
            ) ),
            new ReadRoute( new RegExp( '#^/authorisation/([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}+)$#', [ 'authorisationId' ]), new DetailAuthorisationHandler(
                $this->twig(),
                $this->authorisationRepository(),
                $this->userReference()
            ))

        ];
	}

    /**
     * @Bean
     * @return array
     */
	public function writeRoutes(): array
	{
        $this->setCommandRouting();

		return [
			new WriteRoute( new Literal( '/authorisation/submit' ), new RequestAuthorisationHandler($this->commandBus()) ),
			new WriteRoute( new Literal( '/authorisation/approve' ), new ApproveAuthorisationHandler($this->commandBus(), $this->authorisationRepository()) ),
			new WriteRoute( new Literal( '/authorisation/reject' ), new RejectAuthorisationHandler($this->commandBus(), $this->authorisationRepository()) ),
		];
	}

    /**
     * @Bean
     * @return array
     */
	public function eventSubscribers() : array
	{
		# Register your subscribers for IceHawk events here

		return [
			new IceHawkInitEventSubscriber(),
			new IceHawkReadEventSubscriber(),
			new IceHawkWriteEventSubscriber()
		];
	}

    /**
     * @Bean
     * @return RespondsFinallyToReadRequest
     */
	public function finalReadResponder() : RespondsFinallyToReadRequest
	{
		# Provide a final responder for read requests here

		return new FinalReadResponder();
	}

    /**
     * @Bean
     * @return RespondsFinallyToWriteRequest
     */
	public function finalWriteResponder() : RespondsFinallyToWriteRequest
	{
		# Provide a final responder for write requests here

		return new FinalWriteResponder();
	}

    /**
     * @Bean
     * @return AuthorisationRepository
     */
    public function authorisationRepository(): AuthorisationRepository
    {
        return $this->getAuthorisationRepository($this->eventStore());
    }

    /**
     * @Bean
     * @return AuthorisationOverviewRepository
     */
    public function authorisationOverviewRepository(): AuthorisationOverviewRepository
    {
        return $this->getAuthorisationOverviewRepository($this->eventStore());
    }

    /**
     * @Bean
     * @return CommandBus
     */
    public function commandBus(): CommandBus
    {
        return $this->getCommandBus($this->eventStore());
    }

    /**
     * @Bean
     * @return CommandRouter
     */
    public function commandRouter(): CommandRouter
    {
        return $this->getCommandRouter($this->commandBus());
    }

    /**
     * @Bean
     * @return string
     */
    public function userReference(): string
    {
        return $this->getUserReference($this->securityService(), $this->databaseConnection(), $this->commandBus());
    }

    /**
     * @Bean
     * @return Connection
     */
    public function databaseConnection(): Connection
    {
        $config = require_once __DIR__ . '/../config/database.php';
        return $this->getDatabaseConnection($config);
    }

    /**
     * @Bean
     * @return SlackService
     */
    public function slackService(): SlackService
    {
        $loadedConfig = require_once __DIR__ . '/../config/slack.php';
        return $this->getSlackService($loadedConfig);
    }

    /**
     * @Bean
     * @return SlackMessager
     */
    public function slackMessager(): SlackMessager
    {
        return $this->getMessageProvider(
            $this->slackService(),
            $this->databaseConnection()
        );
    }

    /**
     * @Bean
     * @return \Twig_Environment
     */
    public function twig(): \Twig_Environment
    {
        $config = [
            'pathToTemplates' => __DIR__ . '/Application/Endpoints/Start/Read/Pages'
        ];
        return $this->getTwig($config);
    }
    /**
     * @Bean
     * @return EventRouter
     */
    public function eventRouter(): EventRouter
    {
        return $this->getEventRouter($this->eventStore());
    }

    /**
     * @Bean
     * @return EventStore
     */
    public function eventStore(): EventStore
    {
        return $this->getEventStore($this->databaseConnection());
    }

    /**
     * @Bean
     * @return IceHawk
     */
    public function icehawk(): IceHawk
    {
        $beanFactory = BeanFactoryRegistry::getInstance();
        $config = new IceHawkDiscoConfigDelegate($beanFactory);
        $delegate = new IceHawkDelegate();
        return new IceHawk($config, $delegate);
    }

    /**
     * @Bean
     * @return array
     */
    public function requestBypasses() :array
    {
        return [];
    }

    /**
     * @Bean
     * @return ProvidesCookieData
     */
    public function cookies() : ProvidesCookieData
    {
        return Cookies::fromEnv();
    }

    /**
     * @Bean
     * @return SecurityService
     */
    public function securityService() :SecurityService
    {
        $config = require_once __DIR__ . '/../config/auth0.php';
        return new SecurityService($config);
    }

    /**
     * @Bean
     * @return array|\Traversable|BypassesRequest[]
     */
    public function getRequestBypasses(): array
    {
        return [];
    }
}
