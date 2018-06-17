<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTime;
use VFHousing\UserBundle\Domain\Events\UserActivated;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class UserActivatedSubscriber
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
            'UserActivated',
        ];
    }

    public function onUserActivated(UserActivated $event)
    {
        $dateTime = new DateTime();

        $userIdentity = $event->getUserIdentity();

        /** @var UserProjection $userProjection */
        $userProjection = $this->userRepository->findById($userIdentity->getIdentity());
        $userProjection->setIsEnabled(true);
        $userProjection->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity->getIdentity(), $userProjection);
    }
}