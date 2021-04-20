<?php
declare(strict_types=1);

include dirname(__DIR__).'/vendor/autoload.php';

use Predis\Client;
use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Redis\Message;
use SevenLinX\PubSub\Redis\RedisDriver;



$driver = new RedisDriver(
    new Client(['read_write_timeout' => 0])
);

$messages = [];

$handler = new class {
    public function __invoke(Message $message, $payload)
    {
        var_dump($message->payload());
    }
};

$driver->subscribe(new GenericChannel(), $handler);