<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Codefy\Framework\Http\BaseController;
use Qubus\Http\Session\SessionService;
use Qubus\Routing\Router;
use Qubus\View\Native\Exception\InvalidTemplateNameException;
use Qubus\View\Native\Exception\ViewException;
use Qubus\View\Renderer;

final class HomeController extends BaseController
{
    public function __construct(
        SessionService $sessionService,
        Router $router,
        ?Renderer $view = null
    ) {
        parent::__construct($sessionService, $router, $view);
    }

    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     */
    public function index(): ?string
    {
        return $this->view->render(template: 'framework::home', data: ['title' => 'CodefyPHP Framework']);
    }
}
