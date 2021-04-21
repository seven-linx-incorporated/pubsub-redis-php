<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Tests;

use ArrayIterator;
use stdClass;

class MockPubSubLoop extends ArrayIterator
{
    public function __construct()
    {
        parent::__construct([
            $this->createStdClassMessage('foo', 'test', 'subscribe'),
            $this->createStdClassMessage('bar', 'test'),
        ]);
    }

    private function createStdClassMessage(string $payload, ?string $channel = null, ?string $kind = null): stdClass
    {
        $stdClass = new stdClass();
        $stdClass->kind = $kind ?? 'message';
        $stdClass->payload = $payload;
        $stdClass->channel = $channel ?? 'test';

        return $stdClass;
    }
}