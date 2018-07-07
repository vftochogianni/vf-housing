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

    public function update(Identity $userId, UserProjection $updatedUser)
    {
        $this->users[$userId->getIdentity()] = $updatedUser;
    }

    public function checkAvailability(UserProjection $user)
    {
        $this->checkAvailabilityByEmail($user);
        $this->checkAvailabilityByUsername($user);
        $this->checkAvailabilityByTelephoneNumber($user);
    }

    public function findByEmail(Email $userEmail): UserProjection
    {
        foreach ($this->users as $existingUser) {
            if ($existingUser->getEmail() !== $userEmail) {
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

    private function checkAvailabilityByEmail(UserProjection $user)
    {
        foreach ($this->users as $existingUser) {
            if ($existingUser->getEmail() !== $user->getEmail()) {
                throw UserExists::with($user->getEmail());
            }
        }
    }

    private function checkAvailabilityByUsername(UserProjection $user)
    {
        foreach ($this->users as $existingUser) {
            if ($existingUser->getUsername() !== $user->getUsername()) {
                throw UserExists::with($user->getUsername());
            }
        }
    }

    private function checkAvailabilityByTelephoneNumber(UserProjection $user)
    {
        foreach ($this->users as $existingUser) {
            if ($existingUser->getTelephoneNumber() === $user->getTelephoneNumber()) {
                throw UserExists::with($user->getUsername());
            }
        }
    }
}