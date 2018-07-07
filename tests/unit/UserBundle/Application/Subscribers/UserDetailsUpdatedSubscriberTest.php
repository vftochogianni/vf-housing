<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VFHousing\Core\Identity;
use VFHousing\Tests\Unit\UserFixtures;
use VFHousing\UserBundle\Application\Subscribers\UserRegisteredSubscriber;
use VFHousing\UserBundle\Application\Subscribers\UserDetailsUpdatedSubscriber;
use VFHousing\UserBundle\Infrastructure\Repository\InMemoryUserRepository;

class UserDetailsUpdatedSubscriberTest extends TestCase
{
    public function testOnUserDetailsUpdatedShouldUpdateUserInRepository()
    {
        $userId = Identity::generate('userId');
        $userRegisteredEvent = UserFixtures::createUserRegisteredEvent($userId);
        $repository = new InMemoryUserRepository();
        $userRegisteredSubscriber = new UserRegisteredSubscriber($repository);
        $userRegisteredSubscriber->onUserRegistered($userRegisteredEvent);
        $userUpdatedEvent = UserFixtures::createUserDetailsUpdatedEvent($userId);
        $userUpdatedSubscriber = new UserDetailsUpdatedSubscriber($repository);
        $expected = UserFixtures::updateUserDetailsProjection($userId);

        $userUpdatedSubscriber->onUserDetailsUpdated($userUpdatedEvent);
        $user = $repository->findById($userId);

        $this->assertEquals($expected->getIdentity(), $user->getIdentity());
        $this->assertEquals($expected->getEmail(), $user->getEmail());
        $this->assertEquals($expected->getName(), $user->getName());
        $this->assertEquals($expected->getTelephoneNumber(), $user->getTelephoneNumber());
        $this->assertEquals($expected->getSecurityQuestion(), $user->getSecurityQuestion());
        $this->assertEquals($expected->getSecurityAnswer(), $user->getSecurityAnswer());
        $this->assertContainsOnly(DateTimeImmutable::class, [$user->getCreatedAt(), $user->getUpdatedAt()]);
    }
}
