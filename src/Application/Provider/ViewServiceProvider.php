<?php

declare(strict_types=1);

namespace Application\Provider;

use Codefy\Framework\Support\CodefyServiceProvider;
use Qubus\View\Native\NativeLoader;
use Qubus\View\Renderer;

class ViewServiceProvider extends CodefyServiceProvider
{
    public function register(): void
    {
        $this->codefy->singleton(Renderer::class, function () {
            return new NativeLoader(
                namespaces: $this->codefy->configContainer->array(key: 'view.path'),
                functions: [
                    'trans' => 'Codefy\Framework\Helpers\trans',
                    'html' => 'Codefy\Framework\Helpers\trans_html',
                    'attr' => 'Codefy\Framework\Helpers\trans_attr',
                    'config' => 'Codefy\Framework\Helpers\config',
                    'url' => 'Codefy\Framework\Helpers\site_url',
                ],
                extension: 'phtml'
            );
        });
        $this->codefy->share(nameOrInstance: Renderer::class);
    }
}
