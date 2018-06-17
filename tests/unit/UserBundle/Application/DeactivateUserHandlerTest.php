<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\UserBundle\Application\Commands\DeactivateUser;
use VFHousing\UserBundle\Application\DeactivateUserHandler;
use VFHousing\UserBundle\Domain\DomainEvent;

class DeactivateUserHandlerTest extends TestCase
{
    public function testHandle_ShouldDispatchUserDeactivatedEvent()
    {
        $command = new DeactivateUser('userId');
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $handler = new DeactivateUserHandler($dispatcher->reveal());

        $handler->handle($command);

        $dispatcher->dispatch('UserDeactivated', Argument::type(DomainEvent::class))->shouldHaveBeenCalled();
    }
}
