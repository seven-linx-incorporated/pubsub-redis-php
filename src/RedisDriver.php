<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis;

use Closure;
use Predis\ClientInterface;
use SevenLinX\PubSub\Contracts\ChannelContract;
use SevenLinX\PubSub\Contracts\MessageContract;
use SevenLinX\PubSub\Generics\GenericPayload;
use SevenLinX\PubSub\PubSubDriverInterface;
use stdClass;

use function call_user_func;

final class RedisDriver implements PubSubDriverInterface
{
    /**
     * @var string
     */
    private const MESSAGE = 'message';

    private ?Closure $payloadBuilder = null;

    public function __construct(private ClientInterface $client)
    {
    }

    private function callHandler(stdClass $message, callable $handler): void
    {
        /** @var string $payload */
        $payload = optional($message)->payload;
        /** @var string $channel */
        $channel = optional($message)->channel;

        $handler($this->payloadBuilder !== null
            ? call_user_func($this->payloadBuilder, $payload, $channel)
            : new GenericPayload($payload, $channel)
        );
    }

    public function publish(ChannelContract $channel, MessageContract $message): void
    {
        $this->client->publish($channel->name(), $message->payload());
    }

    public function publishBatch(ChannelContract $channel, MessageContract ...$messages): void
    {
        foreach ($messages as $message) {
            $this->publish($channel, $message);
        }
    }

    public function setPayloadBuilder(callable $builder): void
    {
        $this->payloadBuilder = $builder;
    }

    public function subscribe(ChannelContract $channel, callable $handler): void
    {
        $loop = $this->client->pubSubLoop();
        if ($loop === null) {
            return;
        }

        $loop->subscribe($channel->name());

        /** @var \stdClass $message */
        foreach ($loop as $message) {
            if (property_exists($message, 'kind') === true && $message->kind === self::MESSAGE) {
                $this->callHandler($message, $handler);
            }
        }

        unset($loop);
    }
}