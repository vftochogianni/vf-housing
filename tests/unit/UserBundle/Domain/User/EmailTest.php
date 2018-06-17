<?php

namespace VFHousing\Tests\Unit\UserBundle\Domain\User;

use DomainException;
use VFHousing\UserBundle\Domain\User\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testSet_ShouldReturnValidEmail()
    {
        $email = Email::set('example@domain.com');

        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('example@domain.com', $email->getEmail());
    }

    public function testSet_ShouldThrowException_WhenEmailIsInvalid()
    {
        $email = 'example';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("The provided email '{$email}' is not valid");

        Email::set($email);
    }
}
