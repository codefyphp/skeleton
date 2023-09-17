<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use Codefy\Framework\Http\BaseController;
use Qubus\View\Native\Exception\InvalidTemplateNameException;
use Qubus\View\Native\Exception\ViewException;

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
}
