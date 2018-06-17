<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Commands;

final class UpdateUserDetails
{
    /** @var string */
    private $userIdentity;

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

    public function __construct(
        string $userIdentity,
        string $email,
        string $name,
        string $lastName,
        string $countryCode,
        string $telephoneNumber,
        string $securityQuestion,
        string $securityAnswer
    ){
        $this->userIdentity = $userIdentity;
        $this->email = $email;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->countryCode = $countryCode;
        $this->telephoneNumber = $telephoneNumber;
        $this->securityQuestion = $securityQuestion;
        $this->securityAnswer = $securityAnswer;
    }

    public function getUserIdentity(): string
    {
        return $this->userIdentity;
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
}