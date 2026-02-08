<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Codefy\Framework\Http\BaseController;
use Psr\Http\Message\ResponseInterface;
use Qubus\View\Native\Exception\InvalidTemplateNameException;
use Qubus\View\Native\Exception\ViewException;

use function Codefy\Framework\Helpers\view;

final class HomeController extends BaseController
{
    private string $indexTemplate = 'framework::frontend/home';

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        return view(
            template: $this->indexTemplate,
            data: [
                'title' => 'CodefyPHP Framework'
            ]
        );
    }
}
