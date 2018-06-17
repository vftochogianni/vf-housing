<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use DateTime;
use PHPUnit\Framework\TestCase;
use VFHousing\Core\Identity;
use VFHousing\Tests\Unit\UserFixtures;
use VFHousing\UserBundle\Application\Subscribers\UserRegisteredSubscriber;
use VFHousing\UserBundle\Application\Subscribers\UserDetailsUpdatedSubscriber;
use VFHousing\UserBundle\Infrastructure\Repository\InMemoryUserRepository;

class UserUpdatedSubscriberTest extends TestCase
{
    public function testOnUserUpdateShouldUpdateUserInRepository()
    {
        $userId = Identity::generate('userId');
        $userRegisteredEvent = UserFixtures::createUserRegisteredEvent($userId);
        $repository = new InMemoryUserRepository();
        $userRegisteredSubscriber = new UserRegisteredSubscriber($repository);
        $userRegisteredSubscriber->onUserRegistered($userRegisteredEvent);
        $userUpdatedEvent = UserFixtures::createUserUpdatedEvent($userId);
        $userUpdatedSubscriber = new UserDetailsUpdatedSubscriber($repository);
        $expected = UserFixtures::updateUserProjection($userId);

        $userUpdatedSubscriber->onUserDetailsUpdated($userUpdatedEvent);
        $user = $repository->findById($userId->getIdentity());

        $this->assertEquals($expected->getIdentity(), $user->getIdentity());
        $this->assertEquals($expected->getEmail(), $user->getEmail());
        $this->assertEquals($expected->getName(), $user->getName());
        $this->assertEquals($expected->getTelephoneNumber(), $user->getTelephoneNumber());
        $this->assertEquals($expected->getSecurityQuestion(), $user->getSecurityQuestion());
        $this->assertEquals($expected->getSecurityAnswer(), $user->getSecurityAnswer());
        $this->assertContainsOnly(DateTime::class, [$user->getCreatedAt(), $user->getUpdatedAt()]);
    }
}
