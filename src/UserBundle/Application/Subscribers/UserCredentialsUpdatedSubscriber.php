<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTime;
use VFHousing\UserBundle\Domain\Events\UserCredentialsUpdated;
use VFHousing\UserBundle\Domain\Events\UserDetailsUpdated;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class UserCredentialsUpdatedSubscriber
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'UserCredentialsUpdated'
        ];
    }

    public function onUserCredentialsUpdated(UserCredentialsUpdated $event)
    {
        $dateTime = new DateTime();
        $userIdentity = $event->getUserIdentity();

        /** @var UserProjection $userProjection */
        $userProjection = $this->userRepository->findById($userIdentity->getIdentity());
        $userProjection
            ->setUsername($event->getUsername())
            ->setPassword($event->getPassword())
            ->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity->getIdentity(), $userProjection);
    }
}