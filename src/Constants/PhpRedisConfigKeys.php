<?php
declare(strict_types=1);

namespace SevenLinX\PubSub\Redis\Constants;

final class PhpRedisConfigKeys
{
    /**
     * @var string
     */
    public const CLIENT_NAME = 'name';

    /**
     * @var string
     */
    public const CONTEXT = 'context';

    /**
     * @var string
     */
    public const DATABASE_INDEX = 'database';

    /**
     * @var string
     */
    public const HOST = 'host';

    /**
     * @var string
     */
    public const PASSWORD = 'password';

    /**
     * @var string
     */
    public const PERSISTENT = 'persistent';

    /**
     * @var string
     */
    public const PORT = 'port';

    /**
     * @var string
     */
    public const PREFIX = 'prefix';

    /**
     * @var string
     */
    public const READ_TIMEOUT = 'read_timeout';

    /**
     * @var string
     */
    public const RESERVED = 'reserved';

    /**
     * @var string
     */
    public const RETRY_INTERVAL = 'retry_interval';

    /**
     * @var string
     */
    public const SCAN = 'scan';

    /**
     * @var string
     */
    public const TIMEOUT = 'timeout';
}