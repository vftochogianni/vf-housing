<?php

namespace VFHousing\UserBundle\Domain;

use VFHousing\Core\Identity;
use VFHousing\UserBundle\Domain\User\Email;
use VFHousing\UserBundle\Domain\User\TelephoneNumber;
use VFHousing\UserBundle\Domain\User\Username;

interface UserRepository
{
    public function findById(Identity $userId): UserProjection;

    public function findByEmail(Email $userEmail): UserProjection;

    public function findByUsername(Username $username): UserProjection;

    public function findByTelephoneNumber(TelephoneNumber $telephoneNumber): UserProjection;

    public function findAll(): array;

    public function add(UserProjection $user);

    public function update(Identity $userId, UserProjection $updatedUser);

    public function checkAvailability(UserProjection $user);
}