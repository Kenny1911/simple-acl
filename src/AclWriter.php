<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl;

interface AclWriter
{
    /**
     * @param non-empty-string $role
     */
    public function allow(User $user, Resource $resource, string $role): void;

    /**
     * @param non-empty-string $role
     */
    public function deny(User $user, Resource $resource, string $role): void;
}
