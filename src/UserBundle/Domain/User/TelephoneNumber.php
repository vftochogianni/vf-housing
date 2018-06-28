<?php

namespace VFHousing\UserBundle\Domain\User;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use DomainException;

final class TelephoneNumber
{
    /** @var string */
    private $countryCode;

    /** @var string */
    private $telephoneNumber;

    private function __construct(string $countryCode, string $telephoneNumber)
    {
        $this->countryCode = $this->initiateCountryCode($countryCode);
        $this->telephoneNumber = $this->initiateTelephoneNumber($telephoneNumber);
    }

    public static function set(string $countryCode, string $telephoneNumber): self
    {
        try {
            self::guard($countryCode, $telephoneNumber);
        } catch (InvalidArgumentException $exception) {
            throw new DomainException($exception->getMessage());
        }

        return new self($countryCode, $telephoneNumber);
    }

    public function getTelephoneNumber(): string
    {
        return $this->generateTelephoneNumber();
    }

    private function generateTelephoneNumber(): string
    {
        return $this->countryCode . ' ' . $this->telephoneNumber;
    }

    private static function guard(string $countryCode, string $telephoneNumber)
    {
        Assertion::true(
            empty(preg_replace('/[\+0-9]/', '', $countryCode)),
            "Country code '{$countryCode}' should contain only numbers and/or plus sign (+)"
        );

        Assertion::true(
            empty(preg_replace('/[0-9]/', '', $telephoneNumber)),
            "Telephone number '{$telephoneNumber}' should contain only numbers"
        );
    }

    private function initiateCountryCode(string $countryCode): string
    {
        return '(' . preg_replace('/00/', '+', $countryCode) . ')';
    }

    private function initiateTelephoneNumber(string $telephoneNumber): string
    {
        return implode("-", str_split($telephoneNumber, 3));
    }

    public static function deserialize(string $telephoneNumberAsString): self
    {
        $telephoneNumberAsArray = explode(" ", $telephoneNumberAsString);

        $countryCode =  preg_replace('/[\(\)]/', "", $telephoneNumberAsArray[0]);
        $telephoneNumber = preg_replace("/-/", "", $telephoneNumberAsArray[1]);

        return TelephoneNumber::set($countryCode, $telephoneNumber);
    }
}