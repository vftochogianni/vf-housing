<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Domain;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\EventDispatcher\Event;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\User\Name;
use VFHousing\UserBundle\Domain\User\Password;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;

abstract class DomainEvent extends Event
{
    /** @var Identity */
    protected $userIdentity;

    /** @var Identity */
    protected $identity;

    /** @var DateTime */
    protected $occurredOn;

    /** @var Username */
    protected $username;

    /** @var Password */
    protected $password;

    /** @var Email */
    protected $email;

    /** @var Name */
    protected $name;

    /** @var TelephoneNumber */
    protected $telephoneNumber;

    /** @var SecurityQuestion */
    protected $securityQuestion;

    /** @var bool */
    protected $isEnabled;

    protected function __construct(
        Identity $identity,
        Identity $userIdentity,
        DateTime $occurredOn = null
    ){
        $this->identity = $identity;
        $this->userIdentity = $userIdentity;
        $this->occurredOn = $occurredOn ?: new DateTimeImmutable();
    }

    protected static function getNameFromArray(array $array): Name
    {
        if (str_word_count($array["name"]) == 2) {
            $nameAsArray = explode(" ", $array["name"]);
            return Name::set($nameAsArray[0], $nameAsArray[1]);
        }
        return Name::set($array["name"]);
    }

    protected static function getTelephoneNumberFromArray(array $array): TelephoneNumber
    {
        $telephoneNumberAsString = $array["telephoneNumber"];
        $telephoneNumberAsArray = explode(" ", $telephoneNumberAsString);

        $countryCode = preg_replace('/[\(\)]/', "", $telephoneNumberAsArray[0]);
        $telephoneNumber = preg_replace("/-/", "", $telephoneNumberAsArray[1]);

        return TelephoneNumber::set($countryCode, $telephoneNumber);
    }

    protected static function setOccurredOnFromArray(array $array): DateTime
    {
        if ($array["occurredOn"] != null) {
            return new DateTime(
                $array["occurredOn"]["date"],
                new DateTimeZone($array["occurredOn"]["timezone"])
            );
        }

        return new DateTime;
    }

    public function getUserIdentity(): Identity
    {
        return $this->userIdentity;
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    public function getOccurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    public function serialize(): array
    {
        $serializedEvent = [];

        if (isset($this->identity)) {
            $serializedEvent["identity"] = $this->identity;
        }
        if (isset($this->userIdentity)) {
            $serializedEvent["userIdentity"] = $this->userIdentity;
        }
        if (isset($this->name)) {
            $serializedEvent["name"] = $this->name;
        }
        if (isset($this->username)) {
            $serializedEvent["username"] = $this->username;
        }
        if (isset($this->password)) {
            $serializedEvent["password"] = $this->password;
        }
        if (isset($this->email)) {
            $serializedEvent["email"] = $this->email;
        }
        if (isset($this->telephoneNumber)) {
            $serializedEvent["telephoneNumber"] = $this->telephoneNumber;
        }
        if (isset($this->securityQuestion)) {
            $serializedEvent["securityQuestion"] = $this->securityQuestion->getQuestion();
            $serializedEvent["securityAnswer"] = $this->securityQuestion->getAnswer();
        }
        if (isset($this->occurredOn)) {
            $serializedEvent["occurredOn"] = $this->occurredOn;
        }
        if (isset($this->isEnabled)) {
            $serializedEvent["isEnabled"] = $this->isEnabled;
        }

        return $serializedEvent;
    }

    public function __toString(): string
    {
        return json_encode($this->serialize());
    }

    public abstract function getEventName(): string;
}