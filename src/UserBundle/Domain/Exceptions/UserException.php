<?php
declare(strict_types=1);

namespace VFHousing\UserBundle\Domain\Exceptions;

use DomainException;
use Exception;

final class UserException extends DomainException
{
    public static function causedBy(string $message, Exception $exception = null): self
    {
        if ($exception !== null) {
            $message .= " caused by: {$exception->getMessage()}";
        }

        return new self($message);
    }
}