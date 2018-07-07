<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Domain\Exceptions;

use DomainException;

final class UserDoesNotExist extends DomainException
{
    public static function with(string $type, string $value): self
    {
        return new self("User does not exist with $type '$value'.");
    }
}