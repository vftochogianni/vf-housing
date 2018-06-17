<?php

namespace VFHousing\UserBundle\Domain;

interface UserRepository
{
    public function findById(string $userId);

    public function findAll();

    public function add(UserProjection $user);

    public function update(string $userId, UserProjection $user);
}