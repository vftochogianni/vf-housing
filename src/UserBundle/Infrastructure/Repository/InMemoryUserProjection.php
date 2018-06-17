<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Infrastructure\Repository;

use VFHousing\UserBundle\Domain\Exceptions\UserExists;
use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;

final class InMemoryUserProjection implements UserRepository
{
    /** @var array */
    private $users = [];

    public function findById(string $userId)
    {
        return $this->users[$userId];
    }

    public function findAll()
    {
        return $this->users;
    }

    public function add(UserProjection $user)
    {
        foreach ($this->users as $existingUser) {
            $this->checkAvailability($existingUser, $user);
        }

        $this->users[$user->getIdentity()] = $user->serialize();
        $this->users[$user->getIdentity()]['token'] = bin2hex(random_bytes(72));
    }

    public function update(string $userId, UserProjection $user)
    {
        $this->users[$user->getIdentity()] = $user->serialize();
    }

    private function checkAvailability(UserProjection $existingUser, UserProjection $user)
    {
        if ($existingUser->getUsername() == $user->getUsername()) {
            throw UserExists::with($user->getUsername());
        }

        if ($existingUser->getTelephoneNumber() == $user->getTelephoneNumber()) {
            throw UserExists::with($user->getTelephoneNumber());
        }

        if ($existingUser->getEmail() == $user->getEmail()) {
            throw UserExists::with($user->getEmail());
        }
    }
}