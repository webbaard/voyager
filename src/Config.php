<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager;

use IceHawk\IceHawk\Defaults\Cookies;
use Printdeal\Voyager\Application\Endpoints\Start\Read\LoginRequestHandler;
use IceHawk\IceHawk\Routing\RequestBypasser;
use Printdeal\Voyager\Application\Endpoints\Start\Read\SayHelloRequestHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Write\DoSomethingRequestHandler;
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
use IceHawk\IceHawk\IceHawk;
use IceHawk\IceHawk\Interfaces\ProvidesRequestInfo;
use IceHawk\IceHawk\Interfaces\ProvidesCookieData;
use Printdeal\Voyager\Application\Infra\SecurityService;

/**
 * @Configuration
 */
class Config
{
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
		# Define your write routes (POST / PUT / PATCH / DELETE) here
		# For matching the URI you can use the Literal, RegExp or NamedRegExp pattern classes

		return [
			new WriteRoute( new Literal( '/do-something' ), new DoSomethingRequestHandler() ),
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
			new IceHawkWriteEventSubscriber(),
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
}
