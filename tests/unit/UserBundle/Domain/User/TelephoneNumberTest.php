<?php

namespace VFHousing\Tests\Unit\UserBundle\Domain\User;

use DomainException;
use PHPUnit\Framework\TestCase;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;

class TelephoneNumberTest extends TestCase
{
    public function testSet_ShouldReturnTelephone()
    {
        $telephoneNumber = TelephoneNumber::set('0012', '123456789');

        $this->assertEquals('(+12) 123-456-789', $telephoneNumber->getTelephoneNumber());
    }

    public function testSet_ShouldReturnTelephone_WhenCountryCodeContainsPluSign()
    {
        $telephoneNumber = TelephoneNumber::set('+12', '123456789');

        $this->assertEquals('(+12) 123-456-789', $telephoneNumber->getTelephoneNumber());
    }

    public function testSet_ShouldThrowException_WhenTelephoneNumberDoesNotContainOnlyNumbers()
    {
        $telephoneNumber = 'a23456789';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Telephone number '$telephoneNumber' should contain only numbers");

        TelephoneNumber::set('0012', $telephoneNumber);
    }

    public function testSet_ShouldThrowException_WhenCountryCodeDoesNotContainOnltyNumbersAndPlusSign()
    {
        $countryCode = '0012!';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Country code '{$countryCode}' should contain only numbers and/or plus sign (+)");

        TelephoneNumber::set($countryCode, '123456789');
    }
}
