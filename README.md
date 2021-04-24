# PubSub Redis Driver (PHP)

* * *

## Requirements
- PHP `^8.0`
- [ext-phpredis](https://github.com/phpredis/phpredis) - For Redis extension and `PhpRedisConnector` (__RECOMMENDED__)
- [predis/predis](https://github.com/predis/predis) - For `PredisConnector`

* * *

## Installation

There are two (2) types for Redis client, that needs to install, see [Requirements](#requirements) 
- [ext-phpredis](https://github.com/phpredis/phpredis) - PHP Extension for Redis, need to build PHP
- [predis/predis](https://github.com/predis/predis) - Pure PHP client for Redis

As soon you have any of the client, you may:

```sh
composer require sevenlinx/pubsub-redis-php
```

* * *

## Implement your own Redis connector

You may implement your own Redis client decorator, by implementing `\SevenLinX\PubSub\Redis\Contracts\ConnectorContract`

```php 
use SevenLinX\PubSub\Redis\Contracts\ConnectorContract;

class MyOwnConnector implements ConectorContract
{
    ...
    public function publish(ChannelContract $channel, MessageContract $message): int
    {
        return $this->client->publish($channel->name(), $message->payload());
    }

    public function subscribe(ChannelContract|array $channels, Closure $handler): void
    {
        $this->client->subscribe($channels->name(), [$handler, 'handle']);
    }
}

// subscribe.php
$driver = new RedisDriver(new MyOwnConnector());
$driver->subscribe(new GenericChannel(), function(GenericPayload $payload, redis) {
    var_dump($payload);
});
```
* * *

## Example

You can check on `examples/` directory

__/!\\ NOTE: This requires an existing redis server /!\\__

* * *

## Testing
```shell
composer run testing
```
* * *
###### Created under [Seven LinX Incorporated](https://sevenlinx.tech)