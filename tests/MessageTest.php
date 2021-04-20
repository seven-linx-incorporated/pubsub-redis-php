<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Tests;

use PHPUnit\Framework\TestCase;
use SevenLinX\PubSub\Redis\Message;
use stdClass;

use function json_decode;
use function json_encode;

/**
 * @covers \SevenLinX\PubSub\Redis\Message
 */
final class MessageTest extends TestCase
{
    public function testKind(): void
    {
        $stdClass = new stdClass();
        $message = new Message($stdClass);

        self::assertNull($message->kind());

        $stdClass = new stdClass();
        $stdClass->kind = 'message';
        $message = new Message($stdClass);

        self::assertSame('message', $message->kind());
        self::assertTrue($message->isKind('message'));

        $message = new Message(json_decode('{"kind": "foobar"}'));

        self::assertSame('foobar', $message->kind());
    }

    public function testPayload(): void
    {
        $stdClass = new stdClass();
        $message = new Message($stdClass);

        self::assertNull($message->payload());

        $stdClass = new stdClass();
        $stdClass->payload = 'message';
        $message = new Message($stdClass);

        self::assertSame('message', $message->payload());

        $message = new Message(json_decode('{"payload": "foobar"}'));

        self::assertSame('foobar', $message->payload());

        $stdClass = new stdClass();
        $stdClass->payload = json_encode(['foo' => 'bar'], JSON_THROW_ON_ERROR);
        $message = new Message($stdClass);

        self::assertSame(['foo' => 'bar'], $message->payload());
    }
}
