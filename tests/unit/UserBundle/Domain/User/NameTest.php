<?php

namespace VFHousing\Tests\Unit\UserBundle\Domain\User;

use DomainException;
use PHPUnit\Framework\TestCase;
use VFHousing\Tests\Unit\TestUtilities;
use VFHousing\UserBundle\Domain\User\Name;

class NameTest extends TestCase
{
    use TestUtilities;

    public function testSet_ShouldReturnFullName_WhenSurnameIsNotEmpty()
    {
        $name = Name::set('name', 'surname');

        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals('name surname', $name->getFullName());
    }

    public function testSet_ShouldReturnName_WhenSurnameIsEmpty()
    {
        $name = Name::set('name');

        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals('name', $name->getFullName());
    }

    public function testSet_ShouldThrowException_WhenNameExceedsLengthLimit()
    {
        $name = $this->generateRandomString();
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Name '{$name}' should not exceed 100 characters");

        Name::set($name);
    }

    public function testSet_ShouldThrowException_WhenNameContainsNumbers()
    {
        $name = 'name1';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Name '{$name}' should not contain numbers");

        Name::set($name);
    }

    public function testSet_ShouldThrowException_WhenNameContainsSpecialCharacters()
    {
        $name = 'name!';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Name '{$name}' should not contain special characters");

        Name::set($name);
    }

    public function testSet_ShouldThrowException_WhenFullNameExceedsLengthLimit()
    {
        $name = $this->generateRandomString(50);
        $surname = $this->generateRandomString(50);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Name '{$name} {$surname}' should not exceed 100 characters");

        Name::set($name, $surname);
    }

    public function testDeserialize_ShouldReturnName_WhenFullNameAsStringIsProvided()
    {
        $fullName = 'name lastname';

        $name = Name::deserialize($fullName);

        $this->assertEquals($fullName, $name->getFullName());
    }

    public function testDeserialize_ShouldReturnName_WhenSurnameAsStringIsNotProvided()
    {
        $fullName = 'name';

        $name = Name::deserialize($fullName);

        $this->assertEquals($fullName, $name->getFullName());
    }
}
