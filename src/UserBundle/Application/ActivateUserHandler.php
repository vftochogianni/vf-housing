<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application;

use Composer\Script\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Application\Commands\ActivateUser;
use VFHousing\UserBundle\Domain\User;

final class ActivateUserHandler
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(ActivateUser $command)
    {
        $user = new User();
        $user->activate(Identity::generate($command->getUserIdentity()));

        /** @var Event[] $events */
        $events = $user->getRecordedEvents();

        foreach ($events as $event) {
            $this->dispatcher->dispatch('UserActivated', $event);
        }

        $user->clearRecordedEvents();
    }
}