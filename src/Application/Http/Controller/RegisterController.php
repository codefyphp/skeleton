<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Codefy\Framework\Http\BaseController;
use Domain\User\Validator\StoreUserValidator;
use Domain\User\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Qubus\Http\ServerRequest;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;

use function Codefy\Framework\Helpers\gate;
use function Codefy\Framework\Helpers\trans;
use function Codefy\Framework\Helpers\view;

final class RegisterController extends BaseController
{
    private string $showTemplate = 'framework::frontend/register';

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \Exception
     */
    public function show(): ResponseInterface
    {
        if (true === gate(permission: 'admin:dashboard')) {
            return $this->redirect(
                url: $this->router->url(
                    name: 'admin.home'
                )
            );
        }

        return view(
            template: $this->showTemplate,
            data: [
                'title' => trans('Register'),
                'url' => $this->router->url(name: 'register.create'),
            ]
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function create(ServerRequest $request, UserService $service): ResponseInterface
    {
        if (false === $service->createAccount(StoreUserValidator::make($request))) {
            return $this->redirect(
                url: $this->router->url(
                    name: 'register.show'
                )
            );
        }

        return $this->redirect(
            url: $this->router->url(
                name: 'auth.login'
            )
        );
    }
}
