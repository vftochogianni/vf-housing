<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Application\Commands\UpdateUserCredentials;
use VFHousing\UserBundle\Domain\User;

final class UpdateUserCredentialsHandler
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(UpdateUserCredentials $command)
    {
        $user = new User();
        $user->updateCredentials(
            Identity::generate($command->getUserIdentity()),
            User\Username::set($command->getUsername()),
            User\Password::setFromString($command->getPassword())
        );

        /** @var Event[] $events */
        $events = $user->getRecordedEvents();

        foreach ($events as $event) {
            $this->dispatcher->dispatch('UserCredentialsUpdated', $event);
        }

        $user->clearRecordedEvents();
    }
}