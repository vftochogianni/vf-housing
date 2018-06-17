<?php
declare(strict_types=1);

namespace VFHousing\Tests\Unit\UserBundle\Domain;

use PHPUnit\Framework\TestCase;
use VFHousing\UserBundle\Domain\DomainEvent;
use VFHousing\UserBundle\Domain\Events\UserRegistered;
use VFHousing\UserBundle\Domain\User;
use VFHousing\UserBundle\Domain\UserProjection;

class DomainEventTest extends TestCase
{
    public function testDeserialize_ShouldReturnDomainEvent()
    {
        $eventAsString = '{"identity":"identity","userIdentity":"userIdentity","name":"name surname","username":"username","password":"ecb3548b49fefa9c984ec134fa362b3316ec8cc4c044b3a71444eed538ecc39461fe5d4dd1d71287fcd2b1c3354cc36873956b3e15229b5acbdacda276babed1","email":"example@domain.com","telephoneNumber":"(+30) 123-456-789","securityQuestion":"question","securityAnswer":"answer","occurredOn":{"date":"2017-10-1","timezone":"UTC"},"isEnabled":false}';

        $domainEvent = UserRegistered::deserialize($eventAsString);

        $this->assertInstanceOf(DomainEvent::class, $domainEvent);
        $this->assertEquals('identity', $domainEvent->getIdentity()->getIdentity());
        $this->assertEquals('userIdentity', $domainEvent->getUserIdentity()->getIdentity());
        $this->assertEquals('username', $domainEvent->getUsername()->getUsername());
        $this->assertEquals(hash('sha512', 'Password1!'), $domainEvent->getPassword()->getPassword());
        $this->assertEquals('example@domain.com', $domainEvent->getEmail()->getEmail());
        $this->assertEquals('name surname', $domainEvent->getName()->getFullName());
        $this->assertEquals('(+30) 123-456-789', $domainEvent->getTelephoneNumber()->getTelephoneNumber());
        $this->assertEquals('question', $domainEvent->getSecurityQuestion()->getQuestion());
        $this->assertEquals('answer', $domainEvent->getSecurityQuestion()->getAnswer());
        $this->assertEquals('UTC', $domainEvent->getOccurredOn()->getTimezone()->getName());
    }
}
