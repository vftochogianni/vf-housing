<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use DateTime;
use PHPUnit\Framework\TestCase;
use VFHousing\Core\Identity;
use VFHousing\Tests\Unit\UserFixtures;
use VFHousing\UserBundle\Application\Subscribers\UserRegisteredSubscriber;
use VFHousing\UserBundle\Infrastructure\Repository\InMemoryUserRepository;

class UserRegisteredSubscriberTest extends TestCase
{
    public function testOnUserRegisterShouldAddUserInRepository()
    {
        $userId = Identity::generate('userId');
        $event = UserFixtures::createUserRegisteredEvent($userId);
        $expected = UserFixtures::createUserProjection($userId);
        $repository = new InMemoryUserRepository();
        $subscriber = new UserRegisteredSubscriber($repository);

        $subscriber->onUserRegistered($event);
        $user = $repository->findById($userId->getIdentity());

        $this->assertEquals($expected->getIdentity(), $user->getIdentity());
        $this->assertEquals($expected->getUsername(), $user->getUsername());
        $this->assertEquals($expected->getPassword(), $user->getPassword());
        $this->assertEquals($expected->getEmail(), $user->getEmail());
        $this->assertEquals($expected->getName(), $user->getName());
        $this->assertEquals($expected->getTelephoneNumber(), $user->getTelephoneNumber());
        $this->assertEquals($expected->getSecurityQuestion(), $user->getSecurityQuestion());
        $this->assertEquals($expected->getSecurityAnswer(), $user->getSecurityAnswer());
        $this->assertFalse($user->isEnabled());
        $this->assertContainsOnly(DateTime::class, [$user->getCreatedAt(), $user->getUpdatedAt()]);
    }
}
