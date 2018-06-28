<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Infrastructure\Repository;

use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\Exceptions\UserExists;
use VFHousing\UserBundle\Domain\User;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\User\Password;
use VFHousing\UserBundle\Domain\User\SecurityQuestion;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class InMemoryUserRepository implements UserRepository
{
    /** @var UserProjection[] */
    private $users = [];


    public function findById(Identity $userId): UserProjection
    {
        return $this->users[$userId->getIdentity()];
    }

    public function findAll(): array
    {
        return $this->users;
    }

    public function add(UserProjection $user)
    {
        $this->checkAvailability($user);

        $this->users[$user->getIdentity()] = $user;
    }

    public function update(Identity $userId, UserProjection $user)
    {
        $serializedUser = $user->serialize();
        $securityQuestion = SecurityQuestion::set(
            $serializedUser["securityQuestion"],
            $serializedUser["securityAnswer"]);
        $this->users[$userId->getIdentity()]
            ->setUsername(Username::set($serializedUser['username']))
            ->setPassword(Password::set($serializedUser['password']))
            ->setTelephoneNumber(UserProjection::getTelephoneNumberFromArray($serializedUser))
            ->setName(UserProjection::getNameFromArray($serializedUser))
            ->setSecurityQuestion($securityQuestion)
            ->setSecurityAnswer($securityQuestion)
            ->setEmail(User\Email::set($serializedUser['email']))
            ->setUpdatedAt($user->getUpdatedAt())
            ->setIsEnabled((bool) $serializedUser['isEnabled']);
    }

    public function checkAvailability(UserProjection $user)
    {
        if (!is_null($this->findByEmail(Email::set($user->getEmail())))) {
            throw UserExists::with($user->getEmail());
        }

        if (!is_null($this->findByUsername(Username::set($user->getUsername())))) {
            throw UserExists::with($user->getUsername());
        }

        if (!is_null($this->findByTelephoneNumber(TelephoneNumber::deserialize($user->getTelephoneNumber())))) {
            throw UserExists::with($user->getTelephoneNumber());
        }
    }

    public function findByEmail(Email $userEmail): UserProjection
    {
        foreach ($this->users as $existingUser) {
            if ($existingUser->getEmail() === $userEmail) {
                continue;
            }

            return $existingUser;
        }
    }

    public function findByUsername(Username $username): UserProjection
    {
        foreach ($this->users as $existingUser) {
            if ($existingUser->getUsername() === $username) {
                continue;
            }

            return $existingUser;
        }
    }

    public function findByTelephoneNumber(TelephoneNumber $telephoneNumber): UserProjection
    {
        foreach ($this->users as $existingUser) {
            if ($existingUser->getTelephoneNumber() === $telephoneNumber) {
                continue;
            }

            return $existingUser;
        }
    }
}