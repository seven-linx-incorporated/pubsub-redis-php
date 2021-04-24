<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Tests;

use Closure;
use Mockery;
use PHPUnit\Framework\TestCase;
use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Generics\GenericMessage;
use SevenLinX\PubSub\Redis\Contracts\ConnectorContract;
use SevenLinX\PubSub\Redis\RedisDriver;
use SevenLinX\PubSub\Redis\Tests\Stubs\HandlerStub;

/**
 * @covers \SevenLinX\PubSub\Redis\RedisDriver
 */
final class RedisDriverTest extends TestCase
{
    public function testPublish(): void
    {
        $this->expectNotToPerformAssertions();

        $channel = new GenericChannel('test');
        $message = new GenericMessage('message');

        $connector = Mockery::mock(ConnectorContract::class);
        $connector->shouldReceive('publish')
            ->with($channel, $message)
            ->andReturn(1)
            ->once();

        $driver = new RedisDriver($connector);
        $driver->publish($channel, $message);
    }

    public function testPublishBatch(): void
    {
        $this->expectNotToPerformAssertions();

        $channel = new GenericChannel('test');
        $messages = [
            new GenericMessage('foo'),
            new GenericMessage('bar'),
            new GenericMessage('foo-bar'),
        ];

        $connector = Mockery::mock(ConnectorContract::class);
        $connector->shouldReceive('publish')
            ->with($channel, $messages[0])
            ->once();
        $connector->shouldReceive('publish')
            ->with($channel, $messages[1])
            ->once();
        $connector->shouldReceive('publish')
            ->with($channel, $messages[2])
            ->once();

        $driver = new RedisDriver($connector);
        $driver->publishBatch(
            $channel,
            ...$messages
        );
    }

    public function testSubscribe(): void
    {
        $this->expectNotToPerformAssertions();

        $channel = new GenericChannel('test');
        $callable = Closure::fromCallable(new HandlerStub());

        $connector = Mockery::spy(ConnectorContract::class);
        $connector
            ->shouldReceive('subscribe')
            ->with($channel, $callable)
            ->once();

        $driver = new RedisDriver($connector);
        $driver->subscribe($channel, $callable);
    }
}
