<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use DateTime;
use PHPUnit\Framework\TestCase;
use VFHousing\Core\Identity;
use VFHousing\Tests\Unit\UserFixtures;
use VFHousing\UserBundle\Application\Subscribers\UserActivatedSubscriber;
use VFHousing\UserBundle\Application\Subscribers\UserRegisteredSubscriber;
use VFHousing\UserBundle\Infrastructure\Repository\InMemoryUserRepository;

class UserActivatedSubscriberTest extends TestCase
{
    public function testOnUserActivatedShouldActivateUserInRepository()
    {
        $userId = Identity::generate('userId');
        $userRegisteredEvent = UserFixtures::createUserRegisteredEvent($userId);
        $repository = new InMemoryUserRepository();
        $userRegisteredSubscriber = new UserRegisteredSubscriber($repository);
        $userRegisteredSubscriber->onUserRegistered($userRegisteredEvent);

        $this->assertFalse($repository->findById(Identity::generate($userId->getIdentity()))->isEnabled());

        $userActivatedEvent = UserFixtures::createUserActivatedEvent($userId);
        $userActivatedSubscriber = new UserActivatedSubscriber($repository);
        $userActivatedSubscriber->onUserActivated($userActivatedEvent);
        $expected = UserFixtures::activateUserProjection($userId);

        $user = $repository->findById(Identity::generate($userId->getIdentity()));

        $this->assertEquals($expected->getIdentity(), $user->getIdentity());
        $this->assertTrue($user->isEnabled());
        $this->assertContainsOnly(DateTime::class, [$user->getCreatedAt(), $user->getUpdatedAt()]);
    }
}
