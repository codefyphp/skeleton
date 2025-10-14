<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Codefy\Framework\Http\BaseController;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Qubus\Http\Factories\HtmlResponseFactory;
use Qubus\View\Native\Exception\InvalidTemplateNameException;
use Qubus\View\Native\Exception\ViewException;

final class HomeController extends BaseController
{
    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     * @throws Exception
     */
    public function index(): ResponseInterface
    {
        return HtmlResponseFactory::create(
            $this->view->render(template: 'framework::home', data: ['title' => 'CodefyPHP Framework'])
        );
    }
}
