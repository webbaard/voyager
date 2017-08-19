<?php declare(strict_types = 1);

namespace Printdeal\Voyager;

use Doctrine\DBAL\Connection;
use IceHawk\IceHawk\IceHawk;
use IceHawk\IceHawk\Interfaces\ProvidesCookieData;
use IceHawk\IceHawk\Routing\Interfaces\BypassesRequest;
use IceHawk\IceHawk\Defaults\Cookies;
use Printdeal\Voyager\Application\Endpoints\Start\Read\LoginRequestHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Read\SayHelloRequestHandler;
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
use Printdeal\Voyager\Config\AuthorisationRepositoryProviding;
use Printdeal\Voyager\Config\AuthorisationRouterProviding;
use Printdeal\Voyager\Config\CommandBusProviding;
use Printdeal\Voyager\Config\CommandRouterProviding;
use Printdeal\Voyager\Config\DatabaseProviding;
use Printdeal\Voyager\Config\EventRouterProviding;
use Printdeal\Voyager\Config\EventStoreProviding;
use Printdeal\Voyager\Config\SlackProviding;
use Printdeal\Voyager\Infrastructure\ESAuthorisationRepository;
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
    use AuthorisationRouterProviding;

    use AuthorisationRepositoryProviding;

    /**
     * @Bean
     * @return ProvidesRequestInfo
     */
	public function requestInfo(): ProvidesRequestInfo
    {
        return RequestInfo::fromEnv();
    }

    /**
     * @Bean
     * @return SlackService
     */
    public function slackService(): SlackService
    {
        return $this->getSlackService();
    }

    /**
     * @Bean
     * @return array
     */
	public function readRoutes(): array
	{
		# Define your read routes (GET / HEAD) here
		# For matching the URI you can use the Literal, RegExp or NamedRegExp pattern classes

		return [
			new ReadRoute( new Literal( '/' ), new SayHelloRequestHandler() ),
            new ReadRoute( new Literal( '/auth0/callback'), new LoginRequestHandler() )
		];
	}

    /**
     * @Bean
     * @return array
     */
	public function writeRoutes(): array
	{
        $this->setAuthorisationRouting($this->commandRouter(), $this->authorisationRepository());

		return [
			new WriteRoute( new Literal( '/authorisation/create' ), new RequestAuthorisationHandler($this->commandBus()) ),
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
     * @return ESAuthorisationRepository
     */
    public function authorisationRepository(): ESAuthorisationRepository
    {
        return $this->getAuthorisationRepository($this->eventStore());
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
     * @return Connection
     */
    public function databaseConnection(): Connection
    {
        return $this->getDatabaseConnection();
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
        $config = require_once __DIR__.'/../Auth0Config.php';
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
