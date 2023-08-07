<?php

declare(strict_types=1);

namespace Kenny1911\SimpleAcl\Test;

use Kenny1911\SimpleAcl\ObjectIdentity;
use Kenny1911\SimpleAcl\Resource;
use PHPUnit\Framework\TestCase;

final class ResourceTest extends TestCase
{
    /**
     * @dataProvider dataEquals
     */
    public function testEquals(Resource $resource, ObjectIdentity $obj, bool $expected): void
    {
        $this->assertSame($expected, $resource->equals($obj));
        $this->assertSame($expected, $obj->equals($resource));
    }

    public static function dataEquals(): array
    {
        $resource = new Resource('post', '1');

        return [
            [$resource, $resource, true],
            [$resource, new Resource('post', '1'), true],
            [$resource, new Resource('post', '2'), false],
            [$resource, new Resource('product', '1'), false],
            [$resource, new class ('post', '1') extends ObjectIdentity {}, false],
        ];
    }
}
