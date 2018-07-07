<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Subscribers;

use DateTime;
use DateTimeImmutable;
use VFHousing\UserBundle\Domain\Events\UserRegistered;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class UserRegisteredSubscriber
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
            'UserRegistered'
        ];
    }

    public function onUserRegistered(UserRegistered $event)
    {
        $dateTime = new DateTimeImmutable();

        $userProjection = new UserProjection(
            $event->getUserIdentity(),
            $event->getUsername(),
            $event->getPassword(),
            $event->getEmail(),
            $event->getName(),
            $event->getTelephoneNumber(),
            $event->getSecurityQuestion(),
            $event->isEnabled()
        );

        $this->userRepository->add($userProjection->setCreatedAt($dateTime)->setUpdatedAt($dateTime));
    }
}