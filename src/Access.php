<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl;

final class Access
{
    public readonly User $user;

    public readonly Resource $resource;

    /** @var non-empty-string */
    public readonly string $role;

    /**
     * @param non-empty-string $role
     */
    public function __construct(User $user, Resource $resource, string $role)
    {
        $this->user = $user;
        $this->resource = $resource;
        $this->role = $role;
    }

    public function equals(Access $access): bool
    {
        if ($this === $access) {
            return true;
        }

        return (
            $this->user->equals($access->user) &&
            $this->resource->equals($access->resource) &&
            $this->role === $access->role
        );
    }
}
