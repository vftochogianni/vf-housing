<?php
namespace VFHousing\UserBundle\Domain\Events;

use DateTime;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\DomainEvent;
use VFHousing\UserBundle\Domain\User\Password;
use VFHousing\UserBundle\Domain\User\Username;

final class UserCredentialsUpdated extends DomainEvent
{
    public function __construct(
        Identity $identity,
        Identity $userIdentity,
        Username $username,
        Password $password,
        DateTime $occurredOn = null
    ) {
        parent::__construct($identity, $userIdentity, $occurredOn);

        $this->username = $username;
        $this->password = $password;
    }
    public function getEventName(): string
    {
        return 'UserCredentialsUpdated';
    }

    public static function deserialize(string $serialized): self
    {
        $eventAsArray = json_decode($serialized, true);

        $identity = Identity::generate($eventAsArray["identity"]);
        $userIdentity = Identity::generate($eventAsArray["userIdentity"]);
        $username = Username::set($eventAsArray["username"]);
        $password = Password::set($eventAsArray["password"]);

        $event = new self(
            $identity,
            $userIdentity,
            $username,
            $password,
            self::setOccurredOnFromArray($eventAsArray)
        );

        return $event;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }
}
