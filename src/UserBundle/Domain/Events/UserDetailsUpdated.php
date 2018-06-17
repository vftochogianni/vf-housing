<?php
namespace VFHousing\UserBundle\Domain\Events;

use DateTime;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\DomainEvent;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\User\Name;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;

final class UserDetailsUpdated extends DomainEvent
{
    public function __construct(
        Identity $identity,
        Identity $userIdentity,
        Email $email,
        Name $name,
        TelephoneNumber $telephoneNumber,
        SecurityQuestion $securityQuestion,
        DateTime $occurredOn = null
    ) {
        parent::__construct($identity, $userIdentity, $occurredOn);

        $this->email = $email;
        $this->name = $name;
        $this->telephoneNumber = $telephoneNumber;
        $this->securityQuestion = $securityQuestion;
    }

    public function getEventName(): string
    {
        return 'UserDetailsUpdated';
    }

    public static function deserialize(string $serialized): self
    {
        $eventAsArray = json_decode($serialized, true);

        $identity = Identity::generate($eventAsArray["identity"]);
        $userIdentity = Identity::generate($eventAsArray["userIdentity"]);
        $name = self::getNameFromArray($eventAsArray);
        $email = Email::set($eventAsArray["email"]);
        $telephoneNumber = self::getTelephoneNumberFromArray($eventAsArray);
        $securityQuestion = SecurityQuestion::set($eventAsArray["securityQuestion"], $eventAsArray["securityAnswer"]);

        $event = new self(
            $identity,
            $userIdentity,
            $email,
            $name,
            $telephoneNumber,
            $securityQuestion,
            self::setOccurredOnFromArray($eventAsArray)
        );

        return $event;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getTelephoneNumber(): TelephoneNumber
    {
        return $this->telephoneNumber;
    }

    public function getSecurityQuestion(): SecurityQuestion
    {
        return $this->securityQuestion;
    }
}
