<?php

declare(strict_types=1);

namespace Application\Provider;

use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\Exception\Exception;

use function Codefy\Framework\Helpers\storage_path;

class Psr16ServiceProvider extends CodefyServiceProvider
{
    /**
     * @throws Exception
     */
    public function register(): void
    {
        $adapter = new \Qubus\FileSystem\Adapter\LocalFlysystemAdapter(
            config: $this->codefy->configContainer,
            location: storage_path(path: 'framework/cache')
        );
        $filesystem = new \Qubus\FileSystem\FileSystem(adapter: $adapter);
        $cacheAdapter = new \Qubus\Cache\Adapter\FileSystemCacheAdapter(operator: $filesystem);

        $this->codefy->alias(
            original: \Psr\SimpleCache\CacheInterface::class,
            alias: \Qubus\Cache\Psr16\SimpleCache::class
        );
        $this->codefy->define(name: \Qubus\Cache\Psr16\SimpleCache::class, args: [
            ':adapter' => $cacheAdapter,
            ':ttl' => $this->codefy->configContainer->integer(key: 'cache.ttl'),
            ':namespace' => $this->codefy->configContainer->string(key: 'cache.namespace'),
        ]);
        $this->codefy->share(nameOrInstance: \Psr\SimpleCache\CacheInterface::class);
    }
}
