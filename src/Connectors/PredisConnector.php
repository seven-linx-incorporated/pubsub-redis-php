<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Connectors;

use Closure;
use Predis\Client;
use Predis\PubSub\Consumer;
use SevenLinX\PubSub\Contracts\ChannelContract;
use SevenLinX\PubSub\Contracts\MessageContract;
use SevenLinX\PubSub\Generics\GenericPayload;
use SevenLinX\PubSub\Redis\Concerns\FilterChannelsConcern;
use SevenLinX\PubSub\Redis\Contracts\ConnectorContract;
use stdClass;

use function array_merge;
use function is_array;

final class PredisConnector implements ConnectorContract
{
    use FilterChannelsConcern;

    private Client $client;

    public function __construct(array $config = [], array $options = [])
    {
        $this->client = new Client($config, array_merge([
            'timeout' => 10.0,
            $options,
        ]));
    }

    private function callHandler(stdClass $message, callable $handler): void
    {
        /** @var string $payload */
        $payload = $message->payload;
        /** @var string $channel */
        $channel = $message->channel;

        $handler(new GenericPayload($payload, $channel), $this->client);
    }

    public function publish(ChannelContract $channel, MessageContract $message): int
    {
        return $this->client->publish($channel->name(), $message->payload());
    }

    private function singleSubscribe(ChannelContract $channel, Closure $handler): void
    {
        $loop = $this->client->pubSubLoop();

        if ($loop === null) {
            return;
        }

        $loop->subscribe($channel->name());

        /** @var \stdClass $message */
        foreach ($loop as $message) {
            if (property_exists($message, 'kind') === true && $message->kind === Consumer::MESSAGE) {
                $this->callHandler($message, $handler);
            }
        }

        unset($loop);
    }

    /**
     * @inheritDoc
     */
    public function subscribe(ChannelContract|array $channels, Closure $handler): void
    {
        $channels = is_array($channels) === true ? $this->filterChannels($channels) : [$channels];

        foreach ($channels as $channel) {
            $this->singleSubscribe($channel, $handler);
        }
    }
}