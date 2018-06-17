<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\UserBundle\Application\Commands\UpdateUserDetails;
use VFHousing\UserBundle\Application\UpdateUserDetailsHandler;
use VFHousing\UserBundle\Domain\DomainEvent;

class UpdateUserDetailsHandlerTest extends TestCase
{
    public function testHandle_ShouldUpdateUserRegisteredEvent()
    {
        $command = new UpdateUserDetails(
            'userId',
            'example@domain.com',
            'name',
            'lastName',
            '0030',
            '123456789',
            'question',
            'answer'
        );
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $handler = new UpdateUserDetailsHandler($dispatcher->reveal());

        $handler->handle($command);

        $dispatcher->dispatch('UserUpdated', Argument::type(DomainEvent::class))->shouldHaveBeenCalled();
    }
}
