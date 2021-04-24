<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Connectors;

use Closure;
use LogicException;
use Redis;
use SevenLinX\PubSub\Contracts\ChannelContract;
use SevenLinX\PubSub\Contracts\MessageContract;
use SevenLinX\PubSub\Redis\Concerns\FilterChannelsConcern;
use SevenLinX\PubSub\Redis\Constants\PhpRedisConfigKeys;
use SevenLinX\PubSub\Redis\Contracts\ConnectorContract;
use SevenLinX\PubSub\Redis\Handlers\PhpRedisHandler;

use function is_array;

final class PhpRedisConnector implements ConnectorContract
{
    use FilterChannelsConcern;

    private Redis $client;

    public function __construct(private array $config = [])
    {
        if (extension_loaded('redis') === false) {
            throw new LogicException('Please make sure the PHP Redis extension is installed and enabled.');
        }
        $this->client = new Redis();
        $this->establishConnection();
        $this->configureClient();
    }

    private function establishConnection(): void
    {
        $persistent = $this->config[PhpRedisConfigKeys::PERSISTENT] ?? false;

        $parameters = [
             $this->config[PhpRedisConfigKeys::HOST] ?? '127.0.0.1',
            $this->config[PhpRedisConfigKeys::PORT] ?? 6379,
            (float) ($this->config[PhpRedisConfigKeys::TIMEOUT] ?? 0.0),
            $persistent ? ($this->config[PhpRedisConfigKeys::RESERVED] ?? null) : null,
            $this->config[PhpRedisConfigKeys::RETRY_INTERVAL] ?? 0,
        ];

        if (version_compare(phpversion('redis'), '3.1.3', '>=')) {
            $parameters[] = (float) ($this->config[PhpRedisConfigKeys::READ_TIMEOUT] ?? 0.0);
        }

        if (version_compare(phpversion('redis'), '5.3.0', '>=')) {
            $context = $this->config[PhpRedisConfigKeys::CONTEXT] ?? null;
            if ($context !== null) {
                $parameters[] = $context;
            }
        }
        $connectMethod = $persistent === true ? 'pconnect' : 'connect';

        $this->client->{$connectMethod}(...$parameters);
    }

    private function configureClient(): void
    {
        $password = $this->config[PhpRedisConfigKeys::PASSWORD] ?? null;

        if ($password !== null) {
            $this->client->auth($this->config[PhpRedisConfigKeys::PASSWORD]);
        }

        $this->client->select((int) ($this->config[PhpRedisConfigKeys::DATABASE_INDEX] ?? 0));

        $prefix = $this->config[PhpRedisConfigKeys::PREFIX] ?? null;
        if ($prefix !== null) {
            $this->client->setOption(Redis::OPT_PREFIX, $prefix);
        }

        $scan = $this->config[PhpRedisConfigKeys::SCAN] ?? null;
        if ($scan !== null) {
            $this->client->setOption(Redis::OPT_SCAN, $scan);
        }

        $name = $this->config[PhpRedisConfigKeys::CLIENT_NAME] ?? null;
        if ($name !== null) {
            $this->client->client('SETNAME', $name);
        }
    }

    /**
     * @param  \SevenLinX\PubSub\Contracts\ChannelContract[]  $channels
     *
     * @return string[]
     */
    private function getAllChannelsName(array $channels): array
    {
        return array_map(static fn(ChannelContract $channel) => $channel->name(), $channels);
    }

    public function publish(ChannelContract $channel, MessageContract $message): int
    {
        return $this->client->publish($channel->name(), $message->payload());
    }

    /**
     * @inheritDoc
     */
    public function subscribe(ChannelContract|array $channels, Closure $handler): void
    {
        $channels = is_array($channels) === true ? $this->filterChannels($channels) : [$channels];

        $this->client->subscribe($this->getAllChannelsName($channels), [new PhpRedisHandler($handler), 'handle']);
    }
}