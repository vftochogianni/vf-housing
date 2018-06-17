<?php

namespace VFHousing\Tests\Unit\UserBundle\Domain\User;

use DomainException;
use PHPUnit\Framework\TestCase;
use VFHousing\UserBundle\Domain\User\Password;

class PasswordTest extends TestCase
{
    public function testSetFromString_ShouldReturnHashedPassword()
    {
        $hashedPassword = hash('sha512', 'Password1!');
        $password = Password::setFromString('Password1!');

        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals(
            $hashedPassword,
            $password->getPassword()
        );
    }

    public function testSet_ShouldReturnHashedPassword()
    {
        $hashedPassword = hash('sha512', 'password');
        $password = Password::set($hashedPassword);

        $this->assertInstanceOf(Password::class, $password);
        $this->assertEquals(
            $hashedPassword,
            $password->getPassword()
        );
    }

    public function testSet_ShouldThrowException_WhenPasswordIsLessThanSixCharacters()
    {
        $password = 'Pas1!';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Password '{$password}' should contain at six characters");

        Password::setFromString($password);
    }

    public function testSet_ShouldThrowException_WhenPasswordDoesNotContainNumber()
    {
        $password = 'Password!';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Password '{$password}' should contain at least 1 number");

        Password::setFromString($password);
    }

    public function testSet_ShouldThrowException_WhenPasswordDoesNotContainUpperCaseLetter()
    {
        $password = 'password1!';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Password '{$password}' should contain at least 1 upper case letter");

        Password::setFromString($password);
    }

    public function testSet_ShouldThrowException_WhenPasswordDoesNotContainLowerCaseLetter()
    {
        $password = 'PASSWORD1!';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Password '{$password}' should contain at least 1 lower case letter");

        Password::setFromString($password);
    }

    public function testSet_ShouldThrowException_WhenPasswordDoesNotContainSpecialCharacter()
    {
        $password = 'Password1';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Password '{$password}' should contain at least 1 special character");

        Password::setFromString($password);
    }
}
