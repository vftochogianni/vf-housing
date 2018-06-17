<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\UserBundle\Application\ActivateUserHandler;
use VFHousing\UserBundle\Application\Commands\ActivateUser;
use VFHousing\UserBundle\Domain\DomainEvent;

class ActivateUserHandlerTest extends TestCase
{
    public function testHandle_ShouldDispatchUserDeactivatedEvent()
    {
        $command = new ActivateUser('userId');
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $handler = new ActivateUserHandler($dispatcher->reveal());

        $handler->handle($command);

        $dispatcher->dispatch('UserActivated', Argument::type(DomainEvent::class))->shouldHaveBeenCalled();
    }
}
