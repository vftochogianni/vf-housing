<?php

namespace VFHousing\Tests\Unit\UserBundle\Domain\User;

use DomainException;
use PHPUnit\Framework\TestCase;
use VFHousing\Tests\Unit\TestUtilities;
use VFHousing\UserBundle\Domain\User\Username;

class UsernameTest extends TestCase
{
    use TestUtilities;

    public function testSet_ShouldReturnUsername()
    {
        $username = Username::set('username');

        $this->assertInstanceOf(Username::class, $username);
        $this->assertEquals('username', $username->getUsername());
    }

    public function testSet_ShouldThrowException_WhenUsernameContainsSpecialCharacters()
    {
        $username = 'username!';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Username '{$username}' should not contain special characters");

        Username::set($username);
    }

    public function testSet_ShouldThrowException_WhenUsernameExceedsLengthLimit()
    {
        $username = $this->generateRandomString(51);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Username '{$username}' should not exceed 50 letters");

        Username::set($username);
    }

    public function testSet_ShouldThrowException_WhenUsernameIsLessThan4Characters()
    {
        $username = 'usr';
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Username '{$username}' should be more than 3 letters");

        Username::set($username);
    }
}
