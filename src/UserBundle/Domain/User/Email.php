<?php

namespace VFHousing\UserBundle\Domain\User;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use DomainException;

final class Email
{
    /** @var string */
    private $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function set(string $email): self
    {
        try {
            self::guard($email);
        } catch (InvalidArgumentException $exception) {
            throw new DomainException($exception->getMessage());
        }

        return new self($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    private static function guard(string $email)
    {
        Assertion::email($email, "The provided email '{$email}' is not valid");
    }
}