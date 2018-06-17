<?php
namespace VFHousing\UserBundle\Domain\Events;

use DateTime;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\DomainEvent;

final class UserDeactivated extends DomainEvent
{
    public function __construct(Identity $identity, Identity $userIdentity, DateTime $occurredOn = null)
    {
        parent::__construct($identity, $userIdentity, $occurredOn);
    }

    public function getEventName(): string
    {
        return 'UserDeactivated';
    }

    public static function deserialize(string $serialized): self
    {
        $eventAsArray = json_decode($serialized, true);

        $identity = Identity::generate($eventAsArray["identity"]);
        $userIdentity = Identity::generate($eventAsArray["userIdentity"]);

        $event = new self(
            $identity,
            $userIdentity,
            self::setOccurredOnFromArray($eventAsArray)
        );

        return $event;
    }

    public function isIsEnabled(): bool
    {
        return $this->isEnabled;
    }
}
