<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTime;
use DateTimeImmutable;
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
        $dateTime = new DateTimeImmutable();

        $userIdentity = $event->getUserIdentity();

        /** @var UserProjection $userProjection */
        $userProjection = $this->userRepository->findById($userIdentity);
        $userProjection = $userProjection->setIsEnabled(true);
        $userProjection = $userProjection->setUpdatedAt($dateTime);

        $this->userRepository->update($userIdentity, $userProjection);
    }
}