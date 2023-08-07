<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl\Test\Adapter;

use Kenny1911\SimpleAcl\Access;
use Kenny1911\SimpleAcl\Adapter\Memory;
use Kenny1911\SimpleAcl\Resource;
use Kenny1911\SimpleAcl\User;
use PHPUnit\Framework\TestCase;

final class MemoryTest extends TestCase
{
    public function testIsAllowed(): void
    {
        $user = new User('user', '1');
        $resource = new Resource('post', '1');
        $role = 'read';

        $acl = new Memory([new Access($user, $resource, $role)]);

        $this->assertTrue($acl->isAllowed($user, $resource, $role));
        $this->assertTrue($acl->isAllowed(new User('user', '1'), new Resource('post', '1'), 'read'));
        $this->assertFalse($acl->isAllowed(new User('user', '2'), $resource, $role));
        $this->assertFalse($acl->isAllowed($user, new Resource('post', '2'), $role));
        $this->assertFalse($acl->isAllowed($user, $resource, 'write'));
    }

    public function testFilter(): void
    {
        $accesses = [
            new Access(new User('user', '1'), new Resource('post', '1'), 'read'),
            new Access(new User('user', '1'), new Resource('post', '1'), 'write'),
            new Access(new User('user', '1'), new Resource('post', '2'), 'read'),
            new Access(new User('user', '1'), new Resource('post', '2'), 'write'),
            new Access(new User('user', '2'), new Resource('post', '1'), 'read'),
            new Access(new User('user', '2'), new Resource('post', '1'), 'write'),
            new Access(new User('user', '2'), new Resource('post', '2'), 'read'),
            new Access(new User('user', '2'), new Resource('post', '2'), 'write'),
        ];

        $acl = new Memory($accesses);

        $this->assertEquals($accesses, $acl->filter()->toArray());
        $this->assertEquals(
            [$accesses[0], $accesses[1], $accesses[2], $accesses[3]],
            $acl->filter([new User('user', '1')])->toArray()
        );
        $this->assertEquals(
            [$accesses[0], $accesses[1], $accesses[4], $accesses[5]],
            $acl->filter(resources: [new Resource('post', '1')])->toArray()
        );
        $this->assertEquals(
            [$accesses[0], $accesses[2], $accesses[4], $accesses[6]],
            $acl->filter(roles: ['read'])->toArray()
        );
        $this->assertEquals(
            [$accesses[0], $accesses[4]],
            $acl->filter([new User('user', '1'), new User('user', '2')], [new Resource('post', '1')], ['read'])->toArray()
        );
        $this->assertEquals(
            [$accesses[0]],
            $acl->filter([new User('user', '1'), new User('user', '3')], [new Resource('post', '1'), new Resource('post', '3')], ['read', 'admin'])->toArray()
        );
        $this->assertEquals(
            [],
            $acl->filter([new User('user', '3')], [new Resource('post', '3')], ['admin'])->toArray()
        );
    }

    public function testSlice(): void
    {
        $accesses = [
            new Access(new User('user', '1'), new Resource('post', '1'), 'read'),
            new Access(new User('user', '1'), new Resource('post', '1'), 'write'),
            new Access(new User('user', '1'), new Resource('post', '2'), 'read'),
            new Access(new User('user', '1'), new Resource('post', '2'), 'write'),
            new Access(new User('user', '2'), new Resource('post', '1'), 'read'),
            new Access(new User('user', '2'), new Resource('post', '1'), 'write'),
            new Access(new User('user', '2'), new Resource('post', '2'), 'read'),
            new Access(new User('user', '2'), new Resource('post', '2'), 'write'),
        ];
        $acl = new Memory($accesses);

        $this->assertEquals($accesses, $acl->slice()->toArray());
        $this->assertEquals([$accesses[6], $accesses[7]], $acl->slice(6)->toArray());
        $this->assertEquals([$accesses[1], $accesses[2], $accesses[3]], $acl->slice(1, 3)->toArray());
    }
}
