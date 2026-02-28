<?php

declare(strict_types=1);

namespace Application\Provider;

use Codefy\Framework\Support\CodefyServiceProvider;

use function Codefy\Framework\Helpers\resource_path;

final class VisioBlocksServiceProvider extends CodefyServiceProvider
{
    public function register(): void
    {
        //hidden
        \Visio\Extensions::registerBlock(
            slug: 'blocks-container',
            directoryPath: resource_path(path: 'blocks/layout-blocks-container')
        );
        \Visio\Extensions::registerBlock(
            slug: 'accordion-header',
            directoryPath: resource_path(path: 'blocks/accordion-header')
        );
        \Visio\Extensions::registerBlock(
            slug: 'accordion-body',
            directoryPath: resource_path(path: 'blocks/accordion-body')
        );
        \Visio\Extensions::registerBlock(
            slug: 'carousel-item',
            directoryPath: resource_path(path: 'blocks/carousel-item')
        );
        \Visio\Extensions::registerBlock(
            slug: 'card-heading',
            directoryPath: resource_path(path: 'blocks/card-heading')
        );
        \Visio\Extensions::registerBlock(
            slug: 'card-text',
            directoryPath: resource_path(path: 'blocks/card-text')
        );

        // Layout
        \Visio\Extensions::registerBlock(
            slug: 'container',
            directoryPath: resource_path(path: 'blocks/container')
        );
        \Visio\Extensions::registerBlock(
            slug: 'grid',
            directoryPath: resource_path(path: 'blocks/grid')
        );

        // Basic
        \Visio\Extensions::registerBlock(
            slug: 'heading',
            directoryPath: resource_path(path: 'blocks/heading')
        );
        \Visio\Extensions::registerBlock(
            slug: 'image',
            directoryPath: resource_path(path: 'blocks/image')
        );
        \Visio\Extensions::registerBlock(
            slug: 'text',
            directoryPath: resource_path(path: 'blocks/text')
        );
        \Visio\Extensions::registerBlock(
            slug: 'youtube',
            directoryPath: resource_path(path: 'blocks/youtube')
        );
        \Visio\Extensions::registerBlock(
            slug: 'button',
            directoryPath: resource_path(path: 'blocks/button')
        );
        \Visio\Extensions::registerBlock(
            slug: 'link',
            directoryPath: resource_path(path: 'blocks/link')
        );
        \Visio\Extensions::registerBlock(
            slug: 'divider',
            directoryPath: resource_path(path: 'blocks/divider')
        );
        \Visio\Extensions::registerBlock(
            slug: 'google-map',
            directoryPath: resource_path(path: 'blocks/google-map')
        );
        \Visio\Extensions::registerBlock(
            slug: 'spacer',
            directoryPath: resource_path(path: 'blocks/spacer')
        );
        \Visio\Extensions::registerBlock(
            slug: 'lead',
            directoryPath: resource_path(path: 'blocks/lead')
        );

        // Components
        \Visio\Extensions::registerBlock(
            slug: 'card',
            directoryPath: resource_path(path: 'blocks/card')
        );
        \Visio\Extensions::registerBlock(
            slug: 'accordion',
            directoryPath: resource_path(path: 'blocks/accordion')
        );
        \Visio\Extensions::registerBlock(
            slug: 'content-image',
            directoryPath: resource_path(path: 'blocks/content-image')
        );
        \Visio\Extensions::registerBlock(
            slug: 'carousel',
            directoryPath: resource_path(path: 'blocks/carousel')
        );
    }
}
