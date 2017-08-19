<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\EventSubscribers;

use IceHawk\IceHawk\Events\HandlingReadRequestEvent;
use IceHawk\IceHawk\Events\ReadRequestWasHandledEvent;
use IceHawk\IceHawk\PubSub\AbstractEventSubscriber;

/**
 * Class IceHawkReadEventSubscriber
 * @package Printdeal\Voyager\Application\EventSubscribers
 */
final class IceHawkReadEventSubscriber extends AbstractEventSubscriber
{
	protected function getAcceptedEvents() : array
	{
		return [
			HandlingReadRequestEvent::class,
			ReadRequestWasHandledEvent::class,
		];
	}

	public function whenHandlingReadRequest( HandlingReadRequestEvent $event )
	{
		# This method is called BEFORE the Request handler handles the read Request

		# You can access the Request info via $event->getRequestInfo()
		# You can access the Request input via $event->getRequestInput()
	}

	public function whenReadRequestWasHandled( ReadRequestWasHandledEvent $event )
	{
		# This method is called AFTER the Request handler has handled the read Request

		# You can access the Request info via $event->getRequestInfo()
		# You can access the Request input via $event->getRequestInput()
	}
}
