<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Codefy\Framework\Http\BaseController;
use Codefy\Framework\Proxy\Codefy;
use Psr\Http\Message\ResponseInterface;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;

use function Codefy\Framework\Helpers\gate;
use function Codefy\Framework\Helpers\trans;
use function Codefy\Framework\Helpers\view;

final class AuthController extends BaseController
{
    private string $loginTemplate = 'framework::frontend/login';

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \Exception
     */
    public function auth(): ResponseInterface
    {
        return $this->redirect(
            url: $this->router->url(
                name: 'admin.home'
            )
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \Exception
     */
    public function login(): ResponseInterface
    {
        if (true === gate(permission: 'admin:dashboard')) {
            return $this->redirect($this->router->url(name: 'admin.home'));
        }

        return view(
            template: $this->loginTemplate,
            data: [
                'title' => trans('Login'),
                'url' => $this->router->url(name: 'auth'),
            ]
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \Exception
     */
    public function logout(): ResponseInterface
    {
        if (false === gate(permission: 'admin:dashboard')) {
            Codefy::$PHP->flash->error(
                message: trans('You are already logged out.'),
            );
        }

        return $this->redirect(
            url: $this->router->url(
                name: 'auth.login'
            )
        );
    }
}
