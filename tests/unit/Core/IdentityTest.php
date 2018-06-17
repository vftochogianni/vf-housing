<?php

namespace VFHousing\Tests\Unit\Core;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use VFHousing\Core\Identity;

class IdentityTest extends TestCase
{
    public function testGenerate_ShouldGenerateUUID()
    {
        $identity = Identity::generate();

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertTrue(Uuid::isValid($identity->getIdentity()));
    }

    public function testGenerate_ShouldGenerateCustomIdentity()
    {
        $identity = Identity::generate('custom_identity');

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertFalse(Uuid::isValid($identity->getIdentity()));
        $this->assertEquals('custom_identity', $identity->getIdentity());
    }
}
