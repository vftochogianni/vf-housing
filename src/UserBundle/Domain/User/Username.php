<?php

namespace VFHousing\UserBundle\Domain\User;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use DomainException;

final class Username
{
    /** @var string */
    private $username;

    private function __construct(string $username)
    {
        $this->username = $username;
    }

    public static function set(string $username): self
    {
        try {
            self::guard($username);
        } catch (InvalidArgumentException $exception) {
            throw new DomainException($exception->getMessage());
        }

        return new self($username);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    private static function guard(string $username)
    {
        Assertion::eq(
            0,
            preg_match('/[_+-.,!@#$%^&*();\/|<>"\']/', $username),
            "Username '{$username}' should not contain special characters"
        );

        Assertion::lessOrEqualThan(
            strlen($username),
            50,
            "Username '{$username}' should not exceed 50 letters"
        );

        Assertion::greaterOrEqualThan(
            strlen($username),
            4,
            "Username '{$username}' should be more than 3 letters"
        );
    }
}