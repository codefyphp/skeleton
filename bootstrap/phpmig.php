<?php

declare(strict_types=1);

use Codefy\Framework\Proxy\Codefy;
use Qubus\Expressive\Migration\Adapter\DbalMigrationAdapter;
use Qubus\Support\Container\ObjectStorageMap;

use function Codefy\Framework\Helpers\database_path;
use function Codefy\Framework\Helpers\env;

require __DIR__ . '/../vendor/autoload.php';

$connection = env(key: 'DB_CONNECTION', default: 'default');

$objectmap = new ObjectStorageMap();

$objectmap['connection'] = fn () => Codefy::$PHP->getDbConnection();

$objectmap['phpmig.adapter'] = function ($c) {
    return new DbalMigrationAdapter(connection: $c['connection'], tableName: 'migration');
};

$objectmap['phpmig.migrations_path'] = database_path(path: 'migrations');

return $objectmap;
