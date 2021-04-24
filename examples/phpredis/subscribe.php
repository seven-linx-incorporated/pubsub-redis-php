<?php
declare(strict_types=1);

use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Generics\GenericPayload;
use SevenLinX\PubSub\Redis\Connectors\PhpRedisConnector;
use SevenLinX\PubSub\Redis\RedisDriver;

include dirname(__DIR__).'/../vendor/autoload.php';

$connector = new PhpRedisConnector();

$driver = new RedisDriver($connector);

$driver->subscribe(new GenericChannel(), function (GenericPayload $payload, Redis $redis) {
    print(implode([$payload->payload(), get_class($redis)]));
});
