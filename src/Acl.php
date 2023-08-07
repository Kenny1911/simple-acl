<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl;

use IteratorAggregate;
use Traversable;

final class Acl implements AclReader, AclWriter, IteratorAggregate
{
    private readonly Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function isAllowed(User $user, Resource $resource, string $role): bool
    {
        return $this->adapter->isAllowed($user, $resource, $role);
    }

    public function filter(?array $users = null, ?array $resources = null, ?array $roles = null): AclReader
    {
        return $this->adapter->filter($users, $resources, $roles);
    }

    public function slice(int $offset = 0, ?int $limit = null): AclReader
    {
        return $this->adapter->slice($offset, $limit);
    }

    public function toArray(): array
    {
        return $this->adapter->toArray();
    }

    public function allow(User $user, Resource $resource, string $role): void
    {
        $this->adapter->allow($user, $resource, $role);
    }

    public function deny(User $user, Resource $resource, string $role): void
    {
        $this->adapter->deny($user, $resource, $role);
    }

    public function count(): int
    {
        return $this->adapter->count();
    }

    public function getIterator(): Traversable
    {
        return $this->adapter;
    }
}
