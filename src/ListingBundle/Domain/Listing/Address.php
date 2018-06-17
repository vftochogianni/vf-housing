<?php
declare(strict_types=1);

namespace VFHousing\ListingBundle\Domain\Listing;

use Assert\Assertion;

final class Address
{
    /** @var string */
    private $streetName;
    /** @var int */
    private $streetNumber;
    /** @var string */
    private $streetNumberAddition = '';
    /** @var string */
    private $postalCode;
    /** @var string */
    private $state = '';
    /** @var string */
    private $city;

    private function __construct(
        string $streetName,
        int $streetNumber,
        string $postalCode,
        string $city,
        string $streetNumberAddition = '',
        string $state = ''
    ) {
        $this->streetName = $streetName;
        $this->streetNumber = $streetNumber;
        $this->streetNumberAddition = $streetNumberAddition;
        $this->postalCode = $postalCode;
        $this->state = $state;
        $this->city = $city;
    }

    public static function fromString(string $fullAddress): self
    {
        $addressAsArray = explode(', ', $fullAddress);

        Assertion::greaterThan(
            count($addressAsArray),
            3,
            'The full address provided does not contains all information needed.'
        );
        Assertion::lessThan(
            count($addressAsArray),
            6,
            'The full address provided contains more information than needed.'
        );

        $streetNumber = (int) $addressAsArray[1];
        $streetNumberAdditional = str_replace($streetNumber, '', $addressAsArray[1]) ?: '';

        if (count($addressAsArray) == 4) {
            return self::set(
                $addressAsArray[0],
                $streetNumber,
                $addressAsArray[2],
                $addressAsArray[3],
                $streetNumberAdditional
            );
        }

        if (count($addressAsArray) == 5) {
            return self::set(
                $addressAsArray[0],
                $streetNumber,
                $addressAsArray[2],
                $addressAsArray[3],
                $streetNumberAdditional,
                $addressAsArray[4]
            );
        }
    }

    public static function set(
        string $streetName,
        int $streetNumber,
        string $postalCode,
        string $city,
        string $streetNumberAddition = '',
        string $state = ''
    ): self {
        self::guard($streetName, $streetNumber, $postalCode, $city);

        return new self($streetName, $streetNumber, $postalCode, $city, $streetNumberAddition, $state);
    }

    private static function guard(string $streetName, int $streetNumber, string $postalCode, string $city)
    {
        Assertion::notEmpty($streetName, 'Street name should not be empty.');
        Assertion::notEmpty($streetNumber, 'Street number should not be empty.');
        Assertion::notEmpty($postalCode, 'Postal code should not be empty.');
        Assertion::notEmpty($city, 'City should not be empty.');
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getStreetNumber(): int
    {
        return $this->streetNumber;
    }

    public function getStreetNumberAddition(): string
    {
        return $this->streetNumberAddition;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function __toString(): string
    {
        $address = $this->streetName .
                   ', ' .
                   $this->streetNumber .
                   $this->streetNumberAddition .
                   ', ' .
                   $this->postalCode .
                   ', ' .
                   $this->city;

        if (!empty($this->state)) {
            $address = $address . ', ' . $this->state;
        }

        return $address;
    }
}