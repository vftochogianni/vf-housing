<?php

namespace VFHousing\UserBundle\Domain\User;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use DomainException;

final class Password
{
    const SALT = 'aSpecialSaltForPassword';

    /** @var string */
    private $password;

    private function __construct(string $password)
    {
        $this->password = $password;
    }

    public static function setFromString(string $password): self
    {
        try {
            self::guard($password);
        } catch (InvalidArgumentException $exception) {
            throw new DomainException($exception->getMessage());
        }

        $password = hash('sha512', $password);

        return new self($password);
    }

    public static function set(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }


    public function getPassword(): string
    {
        return $this->password;
    }

    private static function guard(string $password)
    {
        Assertion::greaterOrEqualThan(
            strlen($password),
            6,
            "Password '{$password}' should contain at six characters"
        );

        Assertion::eq(
            1,
            preg_match('/\d/', $password),
            "Password '{$password}' should contain at least 1 number"
        );

        Assertion::eq(
            1,
            preg_match('/[A-Z]/', $password),
            "Password '{$password}' should contain at least 1 upper case letter"
        );

        Assertion::eq(
            1,
            preg_match('/[a-z]/', $password),
            "Password '{$password}' should contain at least 1 lower case letter"
        );

        Assertion::eq(
            1,
            preg_match('/[_+-.,!@#$%^&*();\/|<>"\']/', $password),
            "Password '{$password}' should contain at least 1 special character"
        );
    }
}