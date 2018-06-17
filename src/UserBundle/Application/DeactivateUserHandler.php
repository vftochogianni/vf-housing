<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application;

use Composer\Script\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Application\Commands\DeactivateUser;
use VFHousing\UserBundle\Domain\User;

final class DeactivateUserHandler
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function handle(DeactivateUser $command)
    {
        $user = new User();
        $user->deactivate(Identity::generate($command->getUserIdentity()));

        /** @var Event[] $events */
        $events = $user->getRecordedEvents();

        foreach ($events as $event) {
            $this->dispatcher->dispatch('UserDeactivated', $event);
        }

        $user->clearRecordedEvents();
    }
}