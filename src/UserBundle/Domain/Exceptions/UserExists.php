<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Domain\Exceptions;

use DomainException;

final class UserExists extends DomainException
{
    public static function with(string $value): self
    {
        return new self("'$value' is unavailable.");
    }
}