<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Application\Service\CodefyPageBuilder;
use Codefy\Framework\Http\BaseController;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Qubus\Http\Factories\EmptyResponseFactory;
use Qubus\Http\Factories\HtmlResponseFactory;
use Qubus\Http\Factories\JsonResponseFactory;
use Qubus\Http\ServerRequest;

use function Codefy\Framework\Helpers\config;
use function Codefy\Framework\Helpers\view;
use function dd;
use function Qubus\Support\Helpers\is_null__;

final class PageBuilderController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function assets(): ResponseInterface
    {
        $builder = new CodefyPageBuilder(config('visio'));
        $builder->handlePageBuilderAssetRequest();

        return EmptyResponseFactory::create(200);
    }

    public function uploads(): ResponseInterface
    {
        $builder = new CodefyPageBuilder(config('visio'));
        $builder->handleUploadedFileRequest();

        return EmptyResponseFactory::create(200);
    }

    public function websiteManager(): ResponseInterface
    {
        $builder = new CodefyPageBuilder(config('visio'));
        $builder->handleRequest();

        return EmptyResponseFactory::create(200);
    }

    /**
     * @throws Exception
     */
    public function any(ServerRequest $request): ResponseInterface
    {
        $builder = new CodefyPageBuilder(config('visio'));
        $hasPageReturned = $builder->handlePublicRequest();

        if ($request->getUri()->getPath() === '/' && ! $hasPageReturned) {
            return view(template: 'framework::welcome');
        }

        if (is_null__($hasPageReturned)) {
            return view(template: 'framework::error/404');
        }

        return HtmlResponseFactory::create($hasPageReturned);
    }
}
