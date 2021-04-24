<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Tests\Stubs;

use SevenLinX\PubSub\Generics\GenericPayload;

final class HandlerStub
{
    public function __invoke(GenericPayload $payload): void
    {

    }
}