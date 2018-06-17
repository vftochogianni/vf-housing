<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Application\Commands;

final class UpdateUserCredentials
{
    /** @var string */
    private $userIdentity;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(
        string $userIdentity,
        string $username,
        string $password
    ){
        $this->userIdentity = $userIdentity;
        $this->username = $username;
        $this->password = $password;
    }

    public function getUserIdentity(): string
    {
        return $this->userIdentity;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}