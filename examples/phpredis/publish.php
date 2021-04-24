<?php
declare(strict_types=1);

use SevenLinX\PubSub\Generics\GenericChannel;
use SevenLinX\PubSub\Generics\GenericMessage;
use SevenLinX\PubSub\Redis\Connectors\PhpRedisConnector;
use SevenLinX\PubSub\Redis\RedisDriver;

include dirname(__DIR__).'/../vendor/autoload.php';

$connector = new PhpRedisConnector();

$driver = new RedisDriver($connector);

$driver->publish(new GenericChannel(), new GenericMessage('hoy hoy hoy '.(new DateTime())->getTimestamp()));
