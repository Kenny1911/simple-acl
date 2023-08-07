<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl\Test;

use Kenny1911\SimpleAcl\Access;
use Kenny1911\SimpleAcl\Resource;
use Kenny1911\SimpleAcl\User;
use PHPUnit\Framework\TestCase;

final class AccessTest extends TestCase
{
    /**
     * @dataProvider dataEquals
     */
    public function testEquals(Access $access1, Access $access2, bool $expected): void
    {
        $this->assertSame($expected, $access1->equals($access2));
        $this->assertSame($expected, $access2->equals($access1));
    }

    public static function dataEquals(): array
    {
        $access = new Access(new User('user', '1'), new Resource('post', '1'), 'read');

        return [
            [$access, $access, true],
            [$access, new Access(new User('user', '1'), new Resource('post', '1'), 'read'), true],
            [$access, new Access(new User('user', '2'), new Resource('post', '1'), 'read'), false],
            [$access, new Access(new User('user', '1'), new Resource('post', '2'), 'read'), false],
            [$access, new Access(new User('user', '1'), new Resource('post', '1'), 'write'), false],
        ];
    }
}
