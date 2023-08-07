<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl;

abstract class ObjectIdentity
{
    /** @var non-empty-string */
    public readonly string $type;

    /** @var non-empty-string */
    public readonly string $id;

    /**
     * @param non-empty-string $type
     * @param non-empty-string $id
     */
    public function __construct(string $type, string $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function equals(ObjectIdentity $obj): bool
    {
        if ($this === $obj) {
            return true;
        }

        return (
            static::class === $obj::class &&
            $this->type === $obj->type &&
            $this->id === $obj->id
        );
    }
}
