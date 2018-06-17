<?php

namespace VFHousing\Core;

use Ramsey\Uuid\Uuid;

final class Identity
{
    /** @var UUID|string */
    private $identity;

    private function __construct(string $identity)
    {
        $this->identity = $identity;
    }

    public static function generate(string $identity = null): self
    {
        if (!empty($identity)) {
            return new self($identity);
        }

        return new self(Uuid::uuid4());
    }

    public function getIdentity(): string
    {
        return (string)$this->identity;
    }

    public function __toString()
    {
        return $this->getIdentity();
    }
}