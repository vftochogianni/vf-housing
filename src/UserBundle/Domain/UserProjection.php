<?php

namespace VFHousing\UserBundle\Domain;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\User\Name;
use VFHousing\UserBundle\Domain\User\Password;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;

/**
 * @UniqueEntity("users")
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity(fields="email", message="Email already taken")
 */
final class UserProjection
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", name="identity")
     */
    private $identity;

    /**
     * @var string
     * @ORM\Column(type="string", name="username")
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", name="password")
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, name="email")
     * @Assert\Email(checkMX = true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", name="name")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", name="telephone_number", nullable=true)
     */
    private $telephoneNumber;

    /**
     * @var string
     * @ORM\Column(type="string", name="security_question", nullable=false)
     */
    private $securityQuestion;

    /**
     * @var string
     * @ORM\Column(type="string", name="security_answer", nullable=false)
     */
    private $securityAnswer;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime", name="updated_at", nullable=false)
     */
    private $updatedAt;

    /**
     * @var bool
     * @ORM\Column(name="is_enabled", type="boolean", options={"default"=false})
     */
    private $isEnabled;

    public function __construct(
        Identity $identity,
        Username $username,
        Password $password,
        Email $email,
        Name $name,
        TelephoneNumber $telephoneNumber,
        SecurityQuestion $securityQuestion,
        bool $isEnabled = false
    )
    {
        $this->identity = $identity->getIdentity();
        $this->username = $username->getUsername();
        $this->password = $password->getPassword();
        $this->email = $email->getEmail();
        $this->name = $name->getFullName();
        $this->telephoneNumber = $telephoneNumber->getTelephoneNumber();
        $this->securityQuestion = $securityQuestion->getQuestion();
        $this->securityAnswer = $securityQuestion->getAnswer();
        $this->isEnabled = $isEnabled;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function setIdentity(Identity $identity): self
    {
        $user = clone $this;
        $user->identity = $identity->getIdentity();

        return $user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(Password $password): self
    {
        $user = clone $this;
        $user->password = $password->getPassword();

        return $user;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(Username $username): self
    {
        $user = clone $this;
        $user->username = $username->getUsername();

        return $user;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(Email $email): self
    {
        $user = clone $this;
        $user->email = $email->getEmail();

        return $user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(Name $name): self
    {
        $user = clone $this;
        $user->name = $name->getFullName();

        return $user;
    }

    public function getTelephoneNumber(): string
    {
        return $this->telephoneNumber;
    }

    public function setTelephoneNumber(TelephoneNumber $telephoneNumber): self
    {
        $user = clone $this;
        $user->telephoneNumber = $telephoneNumber->getTelephoneNumber();

        return $user;
    }

    public function getSecurityQuestion(): string
    {
        return $this->securityQuestion;
    }

    public function setSecurityQuestion(SecurityQuestion $securityQuestion): self
    {
        $user = clone $this;
        $user->securityQuestion = $securityQuestion->getQuestion();

        return $user;
    }

    public function getSecurityAnswer(): string
    {
        return $this->securityAnswer;
    }

    public function setSecurityAnswer(SecurityQuestion $securityAnswer): self
    {
        $user = clone $this;
        $user->securityAnswer = $securityAnswer->getAnswer();

        return $user;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $dateTime = null): self
    {
        $user = clone $this;

        if (empty($dateTime)){
            $user->createdAt = new DateTimeImmutable();
            $user->updatedAt = new DateTimeImmutable();
        } else {
            $user->createdAt = $dateTime;
            $user->updatedAt = $dateTime;
        }
        return $user;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $dateTime): self
    {
        $user = clone $this;

        if (empty($dateTime)){
            $user->updatedAt = new DateTimeImmutable();
        } else {
            $user->updatedAt = $dateTime;
        }

        return $user;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $user = clone $this;
        $user->isEnabled = $isEnabled;

        return $user;
    }

    public function __toString(): string
    {
        return json_encode($this->serialize());
    }

    public function serialize(): array
    {
        return [
            "identity" => $this->identity,
            "name" => $this->name,
            "username" => $this->username,
            "password" => $this->password,
            "email" => $this->email,
            "telephoneNumber" => $this->telephoneNumber,
            "securityQuestion" => $this->securityQuestion,
            "securityAnswer" => $this->securityAnswer,
            "createdAt" => $this->createdAt,
            "updatedAt" => $this->updatedAt,
            "isEnabled" => $this->isEnabled,
        ];
    }

    public static function deserialize(string $serialized): self
    {
        $userAsArray = json_decode($serialized, true);

        $identity = Identity::generate($userAsArray["identity"]);
        $name = self::getNameFromArray($userAsArray);
        $username = Username::set($userAsArray["username"]);
        $password = Password::set($userAsArray["password"]);
        $email = Email::set($userAsArray["email"]);
        $telephoneNumber = self::getTelephoneNumberFromArray($userAsArray);
        $securityQuestion = SecurityQuestion::set($userAsArray["securityQuestion"], $userAsArray["securityAnswer"]);
        $isEnabled = (bool) $userAsArray["isEnabled"];

        $user = new self(
            $identity,
            $username,
            $password,
            $email,
            $name,
            $telephoneNumber,
            $securityQuestion,
            $isEnabled
        );

        self::setCreatedAtFromArray($userAsArray, $user);
        self::setUpdatedAtFromArray($userAsArray, $user);

        return $user;
    }

    public static function getNameFromArray(array $array): Name
    {
        if (str_word_count($array["name"]) == 2) {
            $nameAsArray = explode(" ", $array["name"]);
            return Name::set($nameAsArray[0], $nameAsArray[1]);
        }
        return Name::set($array["name"]);
    }

    public static function getTelephoneNumberFromArray(array $array): TelephoneNumber
    {
        return TelephoneNumber::deserialize($array["telephoneNumber"]);
    }

    private static function setCreatedAtFromArray(array $array, UserProjection $user): void
    {
        if ($array["createdAt"] != null) {
            $createdAt = new DateTimeImmutable(
                $array["createdAt"]["date"],
                new DateTimeZone($array["createdAt"]["timezone"])
            );

            $user->setCreatedAt($createdAt);
        }
    }

    private static function setUpdatedAtFromArray(array $array, UserProjection $user): void
    {
        if ($array["updatedAt"] != null) {
            $updatedAt = new DateTimeImmutable(
                $array["updatedAt"]["date"],
                new DateTimeZone($array["updatedAt"]["timezone"])
            );
            $user->setUpdatedAt($updatedAt);
        }
    }
}