<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl\Adapter;

use ArrayIterator;
use IteratorAggregate;
use Kenny1911\SimpleAcl\Access;
use Kenny1911\SimpleAcl\AclReader;
use Kenny1911\SimpleAcl\Adapter;
use Kenny1911\SimpleAcl\Resource;
use Kenny1911\SimpleAcl\User;
use Traversable;

final class Memory implements Adapter, IteratorAggregate
{
    /** @var list<Access> */
    public array $accesses = [];

    /**
     * @param array<Access> $accesses
     */
    public function __construct(array $accesses = [])
    {
        foreach ($accesses as $access) {
            $this->allow($access->user, $access->resource, $access->role);
        }
    }

    public function isAllowed(User $user, Resource $resource, string $role): bool
    {
        foreach ($this->accesses as $access) {
            if ($access->equals(new Access($user, $resource, $role))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param non-empty-array<User>|null $users
     * @param non-empty-array<Resource>|null $resources
     * @param non-empty-array<non-empty-string>|null $roles
     */
    public function filter(?array $users = null, ?array $resources = null, ?array $roles = null): AclReader
    {
        return new Memory(
            array_filter(
                $this->accesses,
                function (Access $access) use ($users, $resources, $roles): bool {
                    $byUser = $byResource = $byRole = false;

                    if (null === $users) {
                        $byUser = true;
                    } else {
                        foreach ($users as $user) {
                            if ($user->equals($access->user)) {
                                $byUser = true;
                            }
                        }
                    }

                    if (null === $resources) {
                        $byResource = true;
                    } else {
                        foreach ($resources as $resource) {
                            if ($resource->equals($access->resource)) {
                                $byResource = true;
                            }
                        }
                    }

                    if (null === $roles) {
                        $byRole = true;
                    } else {
                        foreach ($roles as $role) {
                            if ($access->role === $role) {
                                $byRole = true;
                            }
                        }
                    }

                    return $byUser && $byResource && $byRole;
                }
            )
        );
    }

    public function slice(int $offset = 0, ?int $limit = null): AclReader
    {
        return new Memory(array_slice($this->accesses, $offset, $limit));
    }

    public function toArray(): array
    {
        return $this->accesses;
    }

    public function allow(User $user, Resource $resource, string $role): void
    {
        if (!$this->isAllowed($user, $resource, $role)) {
            $this->accesses[] = new Access($user, $resource, $role);
        }
    }

    public function deny(User $user, Resource $resource, string $role): void
    {
        if ($this->isAllowed($user, $resource, $role)) {
            $this->accesses = array_values(
                array_filter(
                    $this->accesses,
                    fn (Access $access) => !$access->equals(new Access($user, $resource, $role))
                )
            );
        }
    }

    public function count(): int
    {
        return count($this->accesses);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->accesses);
    }
}
