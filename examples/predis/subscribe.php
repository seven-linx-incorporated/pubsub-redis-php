<?php
declare(strict_types=1);

use Predis\Client;
use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Generics\GenericPayload;
use SevenLinX\PubSub\Redis\Connectors\PredisConnector;
use SevenLinX\PubSub\Redis\RedisDriver;

include dirname(__DIR__).'/../vendor/autoload.php';

$connector = new PredisConnector();

$driver = new RedisDriver($connector);

$driver->subscribe(new GenericChannel(), function (GenericPayload $payload, Client $redis) {
    print(implode([$payload->payload(), get_class($redis)]));
});
