<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Generics\GenericMessage;
use SevenLinX\PubSub\Generics\GenericPayload;
use SevenLinX\PubSub\Redis\RedisDriver;
use stdClass;

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

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('publish')
            ->with('test', 'message')
            ->once();

        $driver = new RedisDriver($client);
        $driver->publish($channel, $message);
    }

    public function testPublishBatch(): void
    {
        $this->expectNotToPerformAssertions();

        $channel = new GenericChannel('test');

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('publish')
            ->with('test', 'foo')
            ->once();
        $client->shouldReceive('publish')
            ->with('test', 'bar')
            ->once();
        $client->shouldReceive('publish')
            ->with('test', 'foo-bar')
            ->once();

        $driver = new RedisDriver($client);
        $driver->publishBatch(
            $channel,
            new GenericMessage('foo'),
            new GenericMessage('bar'),
            new GenericMessage('foo-bar')
        );
    }

    public function testSubscribe(): void
    {
        $this->expectNotToPerformAssertions();

        $channel = new GenericChannel('test');

        $loop = Mockery::mock('\\SevenLinX\\PubSub\\Redis\\Tests\\MockPubSubLoop[subscribe]');
        $loop->shouldReceive('subscribe')
            ->with('test')
            ->once();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('pubSubLoop')
            ->once()
            ->andReturn($loop);

        $handler = Mockery::mock(stdClass::class);
        $handler->shouldReceive('handle')
            ->with(GenericPayload::class)
            ->once();

        $driver = new RedisDriver($client);
        $driver->subscribe($channel, [$handler, 'handle']);
    }
}
