<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTimeImmutable;
use VFHousing\UserBundle\Domain\Events\UserCredentialsUpdated;
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
        $dateTime = new DateTimeImmutable();
        $userIdentity = $event->getUserIdentity();

        /** @var UserProjection $userProjection */
        $userProjection = $this->userRepository->findById($userIdentity);
        $userProjection = $userProjection->setUsername($event->getUsername());
        $userProjection = $userProjection->setPassword($event->getPassword());
        $userProjection = $userProjection->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity, $userProjection);
    }
}