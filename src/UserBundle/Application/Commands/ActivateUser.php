<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Commands;

final class ActivateUser
{
    /** @var string */
    private $userIdentity;

    public function __construct(string $userIdentity)
    {
        $this->userIdentity = $userIdentity;
    }

    public function getUserIdentity(): string
    {
        return $this->userIdentity;
    }
}