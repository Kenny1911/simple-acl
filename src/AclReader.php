<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl;

use Countable;
use Traversable;

/**
 * @extends Traversable<Access>
 */
interface AclReader extends Traversable, Countable
{
    /**
     * @param non-empty-string $role
     */
    public function isAllowed(User $user, Resource $resource, string $role): bool;

    /**
     * @param non-empty-array<User>|null $users
     * @param non-empty-array<Resource>|null $resources
     * @param non-empty-array<non-empty-string>|null $roles
     */
    public function filter(?array $users = null, ?array $resources = null, ?array $roles = null): AclReader;

    public function slice(int $offset = 0, ?int $limit = null): AclReader;

    /**
     * @return list<Access>
     */
    public function toArray(): array;
}
