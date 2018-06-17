<?php

namespace VFHousing\UserBundle\Infrastructure\Repository;

use VFHousing\UserBundle\Domain\UserProjection;
use VFHousing\UserBundle\Domain\UserRepository;
use VFHousing\UserBundle\Domain\User;
use VFHousing\Core\Identity;

final class DoctrineUserRepository implements UserRepository
{
    public function findById(string $userId)
    {
        // TODO: Implement findById() method.
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    public function add(UserProjection $user)
    {
        // TODO: Implement add() method.
    }

    public function update(string $userId, UserProjection $user)
    {
        // TODO: Implement update() method.
    }
}