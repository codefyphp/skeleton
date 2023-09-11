<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Codefy\Framework\Http\BaseController;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Qubus\Http\Factories\HtmlResponseFactory;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;
use Qubus\View\Native\Exception\InvalidTemplateNameException;
use Qubus\View\Native\Exception\ViewException;
use Qubus\View\Native\NativeLoader;

use function Codefy\Framework\Helpers\resource_path;

final class HomeController extends BaseController
{
    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function index(): ?string
    {
        return $this->view->render('framework::home', ['title' => 'CodefyPHP Framework']);
    }

    public function redirectToHelloWorld(): ?ResponseInterface
    {
        return $this->redirect('hello-world');
    }

    /**
     * @throws Exception
     */
    public function helloWorld(): ResponseInterface
    {
        return HtmlResponseFactory::create('Hello World!');
    }
}
