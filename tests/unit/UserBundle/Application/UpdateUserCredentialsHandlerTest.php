<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\UserBundle\Application\Commands\UpdateUserCredentials;
use VFHousing\UserBundle\Application\UpdateUserCredentialsHandler;
use VFHousing\UserBundle\Domain\DomainEvent;

class UpdateUserCredentialsHandlerTest extends TestCase
{
    public function testHandle_ShouldDispatchUserCredentialsUpdatedEvent()
    {
        $command = new UpdateUserCredentials(
            'userId',
            'username',
            'Password1!',
            'example@domain.com'
        );
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $handler = new UpdateUserCredentialsHandler($dispatcher->reveal());

        $handler->handle($command);

        $dispatcher->dispatch('UserCredentialsUpdated', Argument::type(DomainEvent::class))->shouldHaveBeenCalled();
    }
}
