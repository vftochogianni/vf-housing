<?php
declare(strict_types=1);

namespace VFHousing\Tests\Unit;

use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\Events\UserActivated;
use VFHousing\UserBundle\Domain\Events\UserCredentialsUpdated;
use VFHousing\UserBundle\Domain\Events\UserDeactivated;
use VFHousing\UserBundle\Domain\Events\UserRegistered;
use VFHousing\UserBundle\Domain\Events\UserDetailsUpdated;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\User\Name;
use VFHousing\UserBundle\Domain\User\Password;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;
use VFHousing\UserBundle\Domain\UserProjection;

final class UserFixtures
{
    public static function createUserRegisteredEvent(Identity $userId): UserRegistered
    {
        return new UserRegistered(
            Identity::generate('id'),
            $userId,
            Username::set('username'),
            Password::set('Password1!'),
            Email::set('example@email.com'),
            Name::set('name'),
            TelephoneNumber::set('0030', '123456789'),
            SecurityQuestion::set('question', 'answer')
        );
    }

    public static function createUserDetailsUpdatedEvent(Identity $userId): UserDetailsUpdated
    {
        return new UserDetailsUpdated(
            Identity::generate('id'),
            $userId,
            Email::set('example@email.com'),
            Name::set('name'),
            TelephoneNumber::set('0030', '123456789'),
            SecurityQuestion::set('question', 'answer')
        );
    }

    public static function createUserCredentialsUpdatedEvent(Identity $userId): UserCredentialsUpdated
    {
        return new UserCredentialsUpdated(
            Identity::generate('id'),
            $userId,
            Username::set('username1'),
            Password::set('pAssw0rd1!')
        );
    }

    public static function createUserDeactivatedEvent(Identity $userId): UserDeactivated
    {
        return new UserDeactivated(Identity::generate('id'), $userId);
    }

    public static function createUserActivatedEvent(Identity $userId): UserActivated
    {
        return new UserActivated(Identity::generate('id'), $userId);
    }

    public static function createUserProjection(Identity $userId): UserProjection
    {
        return new UserProjection(
            $userId,
            Username::set('username'),
            Password::set('Password1!'),
            Email::set('example@email.com'),
            Name::set('name'),
            TelephoneNumber::set('0030', '123456789'),
            SecurityQuestion::set('question', 'answer')
        );
    }

    public static function updateUserDetailsProjection(Identity $userId): UserProjection
    {
        return new UserProjection(
            $userId,
            Username::set('newUsername'),
            Password::set('Password1!'),
            Email::set('example@email.com'),
            Name::set('name'),
            TelephoneNumber::set('0030', '123456789'),
            SecurityQuestion::set('question', 'answer'),
            true
        );
    }

    public static function updateUserCredentialsProjection(Identity $userId): UserProjection
    {
        return new UserProjection(
            $userId,
            Username::set('username1'),
            Password::set('pAssw0rd1!'),
            Email::set('example@email.com'),
            Name::set('name'),
            TelephoneNumber::set('0030', '123456789'),
            SecurityQuestion::set('question', 'answer'),
            true
        );
    }

    public static function deactivateUserProjection(Identity $userId): UserProjection
    {
        return new UserProjection(
            $userId,
            Username::set('newUsername'),
            Password::set('Password1!'),
            Email::set('example@email.com'),
            Name::set('name'),
            TelephoneNumber::set('0030', '123456789'),
            SecurityQuestion::set('question', 'answer'),
            false
        );
    }

    public static function activateUserProjection(Identity $userId): UserProjection
    {
        return new UserProjection(
            $userId,
            Username::set('username'),
            Password::set('Password1!'),
            Email::set('example@email.com'),
            Name::set('name'),
            TelephoneNumber::set('0030', '123456789'),
            SecurityQuestion::set('question', 'answer'),
            true
        );
    }
}