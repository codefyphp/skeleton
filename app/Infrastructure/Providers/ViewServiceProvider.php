<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\View\Native\NativeLoader;
use Qubus\View\Renderer;

class ViewServiceProvider extends CodefyServiceProvider
{
    public function register(): void
    {
        $this->codefy->singleton(Renderer::class, function () {
            return new NativeLoader(
                namespaces: $this->codefy->make(name: 'codefy.config')->getConfigKey(key: 'view.path'),
                functions: [],
                extension: 'phtml'
            );
        });
        $this->codefy->share(nameOrInstance: Renderer::class);
    }
}
