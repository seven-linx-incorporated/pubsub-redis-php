<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Concerns;

use SevenLinX\PubSub\Contracts\ChannelContract;

trait FilterChannelsConcern
{
    /**
     * @param  mixed[]  $channels
     *
     * @return \SevenLinX\PubSub\Contracts\ChannelContract[]
     */
    protected function filterChannels(array $channels): array
    {
        return array_filter($channels, static fn($value) => $value instanceof ChannelContract);
    }
}