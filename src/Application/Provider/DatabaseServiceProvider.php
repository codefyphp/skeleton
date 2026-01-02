<?php

declare(strict_types=1);

namespace Application\Provider;

use Application\Service\DatabaseService;
use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\Expressive\Database;

class DatabaseServiceProvider extends CodefyServiceProvider
{
    public function register(): void
    {
        $this->codefy->singleton(key: Database::class, value: function () {
            return DatabaseService::fromInstance(
                $this->codefy->getDbConnection()
            );
        });
        $this->codefy->share(nameOrInstance: Database::class);
    }
}
