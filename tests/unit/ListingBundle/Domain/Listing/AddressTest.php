<?php
declare(strict_types=1);

namespace VFHousing\Tests\Unit\ListingBundle\Domain\Listing;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use VFHousing\ListingBundle\Domain\Listing\Address;

class AddressTest extends TestCase
{
    public function testSet_ShouldThrowException_WhenStreetNameIsNotSet()
    {
        $this->expectException(InvalidArgumentException::class);

        Address::set('', 1, '1234', 'city');
    }

    public function testSet_ShouldThrowException_WhenPostalCodeIsNotSet()
    {
        $this->expectException(InvalidArgumentException::class);

        Address::set('street name', 1, '', 'city');
    }

    public function testSet_ShouldThrowException_WhenCityIsNotSet()
    {
        $this->expectException(InvalidArgumentException::class);

        Address::set('street name', 1, '1234', '');
    }

    public function testToString_ShouldReturnFullAddress()
    {
        $address = Address::set('street name', 1, '1234', 'city', 'A');

        $this->assertEquals('street name, 1A, 1234, city', (string) $address);
    }

    public function testToString_ShouldReturnFullAddressWithState()
    {
        $address = Address::set('street name', 1, '1234', 'city', 'A', 'state');

        $this->assertEquals('street name, 1A, 1234, city, state', (string) $address);
    }

    public function testFromString_ShouldReturnAddress()
    {
        $fullAddress = 'street name, 1A, 1234, city';

        $address = Address::fromString($fullAddress);

        $this->assertEquals('street name', $address->getStreetName());
        $this->assertEquals(1, $address->getStreetNumber());
        $this->assertEquals('1234', $address->getPostalCode());
        $this->assertEquals('city', $address->getCity());
        $this->assertEquals('A', $address->getStreetNumberAddition());
        $this->assertEmpty($address->getState());
    }

    public function testFromString_ShouldReturnAddressWithState()
    {
        $fullAddress = 'street name, 1, 1234, city, state';

        $address = Address::fromString($fullAddress);

        $this->assertEquals('street name', $address->getStreetName());
        $this->assertEquals(1, $address->getStreetNumber());
        $this->assertEquals('1234', $address->getPostalCode());
        $this->assertEquals('city', $address->getCity());
        $this->assertEmpty($address->getStreetNumberAddition());
        $this->assertEquals('state', $address->getState());
    }
}
