<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Handlers;

use Closure;
use Redis;
use SevenLinX\PubSub\Generics\GenericPayload;

final class PhpRedisHandler
{
    private static Closure $handler;

    public function __construct(Closure $handler)
    {
        self::$handler = $handler;
    }

    public static function handle(Redis $redis, string $channel, mixed $payload): mixed
    {
        return call_user_func(self::$handler, new GenericPayload($payload, $channel), $redis);
    }
}