<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis;

use JsonException;

use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

final class Payload
{
    public function __construct(private string $payload, private string $channel)
    {
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function payload(): mixed
    {
        try {
            return json_decode($this->payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $jsonEncode = json_encode($this->payload, JSON_THROW_ON_ERROR);

            return json_decode($jsonEncode, true, 512, JSON_THROW_ON_ERROR);
        }
    }
}