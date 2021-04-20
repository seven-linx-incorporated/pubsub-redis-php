<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis;

use JsonException;
use stdClass;

use function json_decode;
use function json_encode;
use function property_exists;

final class Message
{
    public function __construct(private stdClass $message)
    {
    }

    public function channel(): ?string
    {
        if (property_exists($this->message, 'channel') === false) {
            return null;
        }

        return $this->message->channel;
    }

    public function isKind(string $kind): bool
    {
        return (($this->kind() === null) === false) && $this->kind() === $kind;
    }

    public function kind(): ?string
    {
        if (property_exists($this->message, 'kind') === false) {
            return null;
        }

        return $this->message->kind;
    }

    /**
     * @throws \JsonException
     */
    public function payload(): mixed
    {
        if (property_exists($this->message, 'payload') === false) {
            return null;
        }
        $payload = $this->message->payload;

        try {
            return json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $jsonEncode = json_encode($payload, JSON_THROW_ON_ERROR);

            return json_decode($jsonEncode, true, 512, JSON_THROW_ON_ERROR);
        }
    }
}