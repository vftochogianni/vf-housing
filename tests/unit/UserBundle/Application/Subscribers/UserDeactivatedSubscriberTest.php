<?php

namespace VFHousing\Tests\Unit\UserBundle\Application;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VFHousing\Core\Identity;
use VFHousing\Tests\Unit\UserFixtures;
use VFHousing\UserBundle\Application\Subscribers\UserActivatedSubscriber;
use VFHousing\UserBundle\Application\Subscribers\UserDeactivatedSubscriber;
use VFHousing\UserBundle\Application\Subscribers\UserRegisteredSubscriber;
use VFHousing\UserBundle\Infrastructure\Repository\InMemoryUserRepository;

class UserDeactivatedSubscriberTest extends TestCase
{
    public function testOnUserDeactivatedShouldDeactivateUserInRepository()
    {
        $userId = Identity::generate('userId');
        $userRegisteredEvent = UserFixtures::createUserRegisteredEvent($userId);
        $repository = new InMemoryUserRepository();
        $userRegisteredSubscriber = new UserRegisteredSubscriber($repository);
        $userRegisteredSubscriber->onUserRegistered($userRegisteredEvent);
        $userUpdatedEvent = UserFixtures::createUserActivatedEvent($userId);
        $userUpdatedSubscriber = new UserActivatedSubscriber($repository);
        $userUpdatedSubscriber->onUserActivated($userUpdatedEvent);

        $this->assertTrue($repository->findById($userId)->isEnabled());

        $userDeactivatedEvent = UserFixtures::createUserDeactivatedEvent($userId);
        $userDeactivatedSubscriber = new UserDeactivatedSubscriber($repository);
        $userDeactivatedSubscriber->onUserDeactivated($userDeactivatedEvent);
        $expected = UserFixtures::deactivateUserProjection($userId);

        $user = $repository->findById($userId);

        $this->assertEquals($expected->getIdentity(), $user->getIdentity());
        $this->assertFalse($user->isEnabled());
        $this->assertContainsOnly(DateTimeImmutable::class, [$user->getCreatedAt(), $user->getUpdatedAt()]);
    }
}
