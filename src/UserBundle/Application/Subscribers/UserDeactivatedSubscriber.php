<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTime;
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
        $dateTime = new DateTime();

        $userIdentity = $event->getUserIdentity();

        /** @var UserProjection $userProjection */
        $userProjection = $this->userRepository->findById($userIdentity->getIdentity());
        $userProjection->setIsEnabled(false);
        $userProjection->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity->getIdentity(), $userProjection);
    }
}