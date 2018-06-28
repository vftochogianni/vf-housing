<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTimeImmutable;
use VFHousing\UserBundle\Domain\Events\UserDeactivated;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class UserDeactivatedSubscriber
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
            'UserDeactivated',
        ];
    }

    public function onUserDeactivated(UserDeactivated $event)
    {
        $dateTime = new DateTimeImmutable();

        $userIdentity = $event->getUserIdentity();

        /** @var UserProjection $userProjection */
        $userProjection = $this->userRepository->findById($userIdentity);
        $userProjection->setIsEnabled(false);
        $userProjection->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity, $userProjection);
    }
}