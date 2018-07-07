<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTimeImmutable;
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
        $dateTime = new DateTimeImmutable();

        $userIdentity = $event->getUserIdentity();

        $userProjection = $this->userRepository->findById($userIdentity);

        $userProjection = $userProjection->setEmail($event->getEmail());
        $userProjection = $userProjection->setTelephoneNumber($event->getTelephoneNumber());
        $userProjection = $userProjection->setName($event->getName());
        $userProjection = $userProjection->setSecurityQuestion($event->getSecurityQuestion());
        $userProjection = $userProjection->setSecurityAnswer($event->getSecurityQuestion());
        $userProjection = $userProjection->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity, $userProjection);
    }
}