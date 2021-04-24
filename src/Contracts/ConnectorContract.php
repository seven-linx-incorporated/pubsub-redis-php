<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Contracts;

use Closure;
use SevenLinX\PubSub\Contracts\ChannelContract;
use SevenLinX\PubSub\Contracts\MessageContract;

interface ConnectorContract
{
    public function publish(ChannelContract $channel, MessageContract $message): int;

    /**
     * @param  \SevenLinX\PubSub\Contracts\ChannelContract|\SevenLinX\PubSub\Contracts\ChannelContract[]  $channels
     */
    public function subscribe(ChannelContract|array $channels, Closure $handler): void;
}