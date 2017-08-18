<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager;

use bitExpert\Disco\AnnotationBeanFactory;
use bitExpert\Disco\BeanFactoryRegistry;
use Printdeal\Voyager\Application\Endpoints\Start\Read\LoginRequestHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Read\SayHelloRequestHandler;
use Printdeal\Voyager\Application\Endpoints\Start\Write\DoSomethingRequestHandler;
use Printdeal\Voyager\Application\EventSubscribers\IceHawkInitEventSubscriber;
use Printdeal\Voyager\Application\EventSubscribers\IceHawkReadEventSubscriber;
use Printdeal\Voyager\Application\EventSubscribers\IceHawkWriteEventSubscriber;
use Printdeal\Voyager\Application\FinalResponders\FinalReadResponder;
use Printdeal\Voyager\Application\FinalResponders\FinalWriteResponder;
use IceHawk\IceHawk\Defaults\Traits\DefaultCookieProviding;
use IceHawk\IceHawk\Defaults\Traits\DefaultRequestBypassing;
use IceHawk\IceHawk\Defaults\Traits\DefaultRequestInfoProviding;
use IceHawk\IceHawk\Interfaces\ConfiguresIceHawk;
use IceHawk\IceHawk\Interfaces\RespondsFinallyToReadRequest;
use IceHawk\IceHawk\Interfaces\RespondsFinallyToWriteRequest;
use IceHawk\IceHawk\Routing\Patterns\Literal;
use IceHawk\IceHawk\Routing\ReadRoute;
use IceHawk\IceHawk\Routing\WriteRoute;
use Printdeal\Voyager\Application\Infra\Configuration;

/**
 * Class IceHawkConfig
 * @package Printdeal\Voyager
 */
final class IceHawkConfig implements ConfiguresIceHawk
{
	use DefaultRequestInfoProviding;
	use DefaultCookieProviding;
	use DefaultRequestBypassing;

	public function __construct()
    {
        $beanFactory = new AnnotationBeanFactory(Configuration::class);
        BeanFactoryRegistry::register($beanFactory);
    }

    public function getReadRoutes()
	{
		# Define your read routes (GET / HEAD) here
		# For matching the URI you can use the Literal, RegExp or NamedRegExp pattern classes

		return [
			new ReadRoute( new Literal( '/' ), new SayHelloRequestHandler() ),
            new ReadRoute( new Literal( '/auth0/callback'), new LoginRequestHandler() )
		];
	}

	public function getWriteRoutes()
	{
		# Define your write routes (POST / PUT / PATCH / DELETE) here
		# For matching the URI you can use the Literal, RegExp or NamedRegExp pattern classes

		return [
			new WriteRoute( new Literal( '/do-something' ), new DoSomethingRequestHandler() ),
		];
	}

	public function getEventSubscribers() : array
	{
		# Register your subscribers for IceHawk events here

		return [
			new IceHawkInitEventSubscriber(),
			new IceHawkReadEventSubscriber(),
			new IceHawkWriteEventSubscriber(),
		];
	}

	public function getFinalReadResponder() : RespondsFinallyToReadRequest
	{
		# Provide a final responder for read requests here

		return new FinalReadResponder();
	}

	public function getFinalWriteResponder() : RespondsFinallyToWriteRequest
	{
		# Provide a final responder for write requests here

		return new FinalWriteResponder();
	}
}
