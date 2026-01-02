<?php

declare(strict_types=1);

namespace Application\Http\Controllers;

use Codefy\Framework\Http\BaseController;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Qubus\View\Native\Exception\InvalidTemplateNameException;
use Qubus\View\Native\Exception\ViewException;

use function Codefy\Framework\Helpers\view;

final class HomeController extends BaseController
{
    /**
     * @throws ViewException
     * @throws InvalidTemplateNameException
     * @throws Exception
     */
    public function index(): ResponseInterface
    {
        return view(template: 'framework::home', data: ['title' => 'CodefyPHP Framework']);
    }
}
