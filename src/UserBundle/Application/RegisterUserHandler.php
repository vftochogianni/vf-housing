<?php
declare(strict_types = 1);

namespace VFHousing\UserBundle\Application;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Application\Commands\RegisterUser;
use VFHousing\UserBundle\Domain\User;

final class RegisterUserHandler
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(RegisterUser $command)
    {
        $user = new User();
        $user->register(
            Identity::generate($command->getUserIdentity()),
            User\Username::set($command->getUsername()),
            User\Password::setFromString($command->getPassword()),
            User\Email::set($command->getEmail()),
            User\Name::set($command->getName(), $command->getLastName()),
            User\TelephoneNumber::set($command->getCountryCode(), $command->getTelephoneNumber()),
            User\SecurityQuestion::set($command->getSecurityQuestion(), $command->getSecurityAnswer())
        );

        /** @var Event[] $events */
        $events = $user->getRecordedEvents();

        foreach ($events as $event) {
            $this->dispatcher->dispatch('UserRegistered', $event);
        }

        $user->clearRecordedEvents();
    }
}