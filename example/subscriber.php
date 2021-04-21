<?php
declare(strict_types=1);

include dirname(__DIR__).'/vendor/autoload.php';

use Predis\Client;
use SevenLinX\PubSub\Contracts\PayloadContract;
use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Redis\Payload;
use SevenLinX\PubSub\Redis\RedisDriver;

$driver = new RedisDriver(
    new Client(['read_write_timeout' => 0])
);

$messages = [];

$handler = new class {
    public function __invoke($payload)
    {
        var_dump($payload);
//        var_dump($payload->payload());
    }
};

$payloadBuilder = new class {
    public function __invoke(string $payload, $channel)
    {
        return unserialize($payload, ['allowed_classes' => true]);
    }
};
$driver->setPayloadBuilder(function (string $payload) {
    return unserialize($payload, ['allowed_classes' => true]);
});
$driver->subscribe(new GenericChannel(), $handler);