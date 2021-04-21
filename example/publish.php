<?php
declare(strict_types=1);

include dirname(__DIR__).'/vendor/autoload.php';

use Predis\Client;
use SevenLinX\PubSub\Contracts\MessageContract;
use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Generics\GenericMessage;
use SevenLinX\PubSub\Redis\RedisDriver;

$driver = new RedisDriver(
    new Client(['read_write_timeout' => 0])
);

$message = new class implements MessageContract {
    public function payload(): string
    {
        return serialize(['foo' => 'bar']);
    }
};
$driver->publish(new GenericChannel(), $message);
//$driver->publish(new GenericChannel(), new GenericMessage());
//$driver->publish(new ChannelStub(), new Message('foo-bar'));
//$driver->publish(new ChannelStub(), new MessageStub());