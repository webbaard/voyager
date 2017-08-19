<?php declare(strict_types = 1);
/**
 * @author tim.huijzers <tim.huijzers@drukwerkdeal.nl>
 */

namespace Printdeal\Voyager\Application\EventSubscribers;

use IceHawk\IceHawk\Events\HandlingWriteRequestEvent;
use IceHawk\IceHawk\Events\WriteRequestWasHandledEvent;
use IceHawk\IceHawk\PubSub\AbstractEventSubscriber;

/**
 * Class IceHawkWriteEventSubscriber
 * @package Printdeal\Voyager\Application\EventSubscribers
 */
final class IceHawkWriteEventSubscriber extends AbstractEventSubscriber
{
	protected function getAcceptedEvents() : array
	{
		return [
			HandlingWriteRequestEvent::class,
			WriteRequestWasHandledEvent::class,
		];
	}

	public function whenHandlingWriteRequest( HandlingWriteRequestEvent $event )
	{
		# This method is called BEFORE the Request handler handles the write Request

		# You can access the Request info via $event->getRequestInfo()
		# You can access the Request input via $event->getRequestInput()
	}

	public function whenWriteRequestWasHandled( WriteRequestWasHandledEvent $event )
	{
		# This method is called AFTER the Request handler has handled the write Request

		# You can access the Request info via $event->getRequestInfo()
		# You can access the Request input via $event->getRequestInput()
	}
}
