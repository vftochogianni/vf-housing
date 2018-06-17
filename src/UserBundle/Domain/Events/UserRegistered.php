<?php
namespace VFHousing\UserBundle\Domain\Events;

use DateTime;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\DomainEvent;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\User\Name;
use VFHousing\UserBundle\Domain\User\Password;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;

final class UserRegistered extends DomainEvent
{
    public function __construct(
        Identity $identity,
        Identity $userIdentity,
        Username $username,
        Password $password,
        Email $email,
        Name $name,
        TelephoneNumber $telephoneNumber,
        SecurityQuestion $securityQuestion,
        bool $isEnabled = null,
        DateTime $occurredOn = null
    ){
        parent::__construct($identity, $userIdentity, $occurredOn);

        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->name = $name;
        $this->telephoneNumber = $telephoneNumber;
        $this->securityQuestion = $securityQuestion;
        $this->isEnabled = $isEnabled ?: false;
    }

    public function getEventName(): string
    {
        return 'UserRegistered';
    }

    public static function deserialize(string $serialized): self
    {
        $eventAsArray = json_decode($serialized, true);

        $identity = Identity::generate($eventAsArray["identity"]);
        $userIdentity = Identity::generate($eventAsArray["userIdentity"]);
        $name = self::getNameFromArray($eventAsArray);
        $username = Username::set($eventAsArray["username"]);
        $password = Password::set($eventAsArray["password"]);
        $email = Email::set($eventAsArray["email"]);
        $telephoneNumber = self::getTelephoneNumberFromArray($eventAsArray);
        $securityQuestion = SecurityQuestion::set($eventAsArray["securityQuestion"], $eventAsArray["securityAnswer"]);
        $isEnabled = $eventAsArray["isEnabled"];

        $event = new self(
            $identity,
            $userIdentity,
            $username,
            $password,
            $email,
            $name,
            $telephoneNumber,
            $securityQuestion,
            $isEnabled,
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

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}