<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Commands;

final class RegisterUser
{
    /** @var string */
    private $userIdentity;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $email;

    /** @var string */
    private $name;

    /** @var string */
    private $lastName;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $telephoneNumber;

    /** @var string */
    private $securityQuestion;

    /** @var string */
    private $securityAnswer;

    /** @var bool */
    private $isEnabled;

    public function __construct(
        string $userIdentity,
        string $username,
        string $password,
        string $email,
        string $name,
        string $lastName,
        string $countryCode,
        string $telephoneNumber,
        string $securityQuestion,
        string $securityAnswer,
        bool $isEnabled = false
    ){
        $this->userIdentity = $userIdentity;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->countryCode = $countryCode;
        $this->telephoneNumber = $telephoneNumber;
        $this->securityQuestion = $securityQuestion;
        $this->securityAnswer = $securityAnswer;
        $this->isEnabled = $isEnabled;
    }

    public function getUserIdentity(): string
    {
        return $this->userIdentity;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getTelephoneNumber(): string
    {
        return $this->telephoneNumber;
    }

    public function getSecurityQuestion(): string
    {
        return $this->securityQuestion;
    }

    public function getSecurityAnswer(): string
    {
        return $this->securityAnswer;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}