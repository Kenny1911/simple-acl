<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl\Test;

use Kenny1911\SimpleAcl\ObjectIdentity;
use Kenny1911\SimpleAcl\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    /**
     * @dataProvider dataEquals
     */
    public function testEquals(User $user, ObjectIdentity $obj, bool $expected): void
    {
        $this->assertSame($expected, $user->equals($obj));
        $this->assertSame($expected, $obj->equals($user));
    }

    public static function dataEquals(): array
    {
        $user = new User('user', '1');

        return [
            [$user, $user, true],
            [$user, new User('user', '1'), true],
            [$user, new User('user', '2'), false],
            [$user, new User('user2', '1'), false],
            [$user, new class ('user', '1') extends ObjectIdentity {}, false],
        ];
    }
}
