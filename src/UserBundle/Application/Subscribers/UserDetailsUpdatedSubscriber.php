<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTime;
use VFHousing\UserBundle\Domain\Events\UserDetailsUpdated;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class UserDetailsUpdatedSubscriber
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
            'UserDetailsUpdated',
        ];
    }

    public function onUserDetailsUpdated(UserDetailsUpdated $event)
    {
        $dateTime = new DateTime();

        $userIdentity = $event->getUserIdentity();
        /** @var UserProjection $userProjection */
        $userProjection = $this->userRepository->findById($userIdentity->getIdentity());

        $userProjection
            ->setEmail($event->getEmail())
            ->setTelephoneNumber($event->getTelephoneNumber())
            ->setName($event->getName())
            ->setSecurityQuestion($event->getSecurityQuestion())
            ->setSecurityAnswer($event->getSecurityQuestion())
            ->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity->getIdentity(), $userProjection);
    }
}