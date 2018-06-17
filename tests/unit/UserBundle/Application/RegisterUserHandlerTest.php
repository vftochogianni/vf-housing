<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\UserBundle\Application\Commands\RegisterUser;
use VFHousing\UserBundle\Application\RegisterUserHandler;
use VFHousing\UserBundle\Domain\DomainEvent;

class RegisterUserHandlerTest extends TestCase
{
    public function testHandle_ShouldCreateUserRegisteredEvent()
    {
        $command = new RegisterUser(
            'userId',
            'username',
            'Password1!',
            'example@domail.com',
            'name',
            'lastName',
            '0030',
            '123456789',
            'question',
            'answer'
        );
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $handler = new RegisterUserHandler($dispatcher->reveal());

        $handler->handle($command);

        $dispatcher->dispatch('UserRegistered', Argument::type(DomainEvent::class))->shouldHaveBeenCalled();
    }
}
