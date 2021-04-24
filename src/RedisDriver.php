<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis;

use SevenLinX\PubSub\Contracts\ChannelContract;
use SevenLinX\PubSub\Contracts\MessageContract;
use SevenLinX\PubSub\PubSubDriverInterface;
use SevenLinX\PubSub\Redis\Contracts\ConnectorContract;

final class RedisDriver implements PubSubDriverInterface
{
    public function __construct(private ConnectorContract $connector)
    {
    }

    public function publish(ChannelContract $channel, MessageContract $message): void
    {
        $this->connector->publish($channel, $message);
    }

    public function publishBatch(ChannelContract $channel, MessageContract ...$messages): void
    {
        foreach ($messages as $message) {
            $this->publish($channel, $message);
        }
    }

    public function subscribe(ChannelContract $channel, callable $handler): void
    {
        $this->connector->subscribe($channel, $handler);
    }
}