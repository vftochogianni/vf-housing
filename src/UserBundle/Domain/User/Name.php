<?php

namespace VFHousing\UserBundle\Domain\User;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use DomainException;

final class Name
{
    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    private function __construct(string $name, string $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    public static function set(string $name, string $surname = ''): self
    {
        try {
            $nameAsArray = self::guard($name, $surname);
        } catch (InvalidArgumentException $exception) {
            throw new DomainException($exception->getMessage());
        }

        return new self($nameAsArray[0], $nameAsArray[1]);
    }

    private function getName(): string
    {
        return $this->name;
    }

    private function getSurname(): string
    {
        return $this->surname;
    }

    public function getFullName(): string
    {
        if (empty($this->getSurname())) {
            return $this->getName();
        }

        return $this->getName() . ' ' . $this->getSurname();
    }

    private static function guard(string $name, string $surname = ''): array
    {
        $array = [];
        $name = preg_replace('/\s/', "", $name);
        $array[] = $name;

        if (!empty($surname)) {
            $surname = preg_replace('/\s/', "", $surname);
            $name = $name . ' ' . $surname;
        }
        $array[] = $surname;

        Assertion::lessOrEqualThan(
            strlen($name),
            100,
            "Name '{$name}' should not exceed 100 characters"
        );
        Assertion::eq(
            0,
            preg_match('/\d/', $name),
            "Name '{$name}' should not contain numbers"
        );

        Assertion::eq(
            0,
            preg_match('/[_+-.,!@#$%^&*();\/|<>"\']/', $name),
            "Name '{$name}' should not contain special characters"
        );

        return $array;
    }
}