<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Infrastructure\Repository;

use VFHousing\UserBundle\Domain\Exceptions\UserExists;
use VFHousing\UserBundle\Domain\User;
use VFHousing\UserBundle\Domain\User\Name;
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

        $this->users[$user->getIdentity()] = $user;
    }

    public function update(string $userId, UserProjection $user)
    {
        $userAsArray = $user->serialize();
        $securityQuestion = SecurityQuestion::set($userAsArray["securityQuestion"], $userAsArray["securityAnswer"]);
        $this->users[$userId]
            ->setUsername(Username::set($userAsArray['username']))
            ->setPassword(Password::set($userAsArray['password']))
            ->setTelephoneNumber(UserProjection::getTelephoneNumberFromArray($userAsArray))
            ->setName(UserProjection::getNameFromArray($userAsArray))
            ->setSecurityQuestion($securityQuestion)
            ->setSecurityAnswer($securityQuestion)
            ->setEmail(User\Email::set($userAsArray['email']))
            ->setUpdatedAt($user->getUpdatedAt())
            ->setIsEnabled((bool) $userAsArray['isEnabled']);
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