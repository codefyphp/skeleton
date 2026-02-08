<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Codefy\Framework\Http\BaseController;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Domain\User\Enum\UserRole;
use Domain\User\Validator\DestroyUserValidator;
use Domain\User\Validator\StoreUserValidator;
use Domain\User\Validator\UpdateUserValidator;
use Domain\User\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Qubus\Http\ServerRequest;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;

use function array_values;
use function Codefy\Framework\Helpers\trans;
use function Codefy\Framework\Helpers\view;
use function json_encode;

final class AdminController extends BaseController
{
    private string $dashboardTemplate = 'framework::backend/index';
    private string $usersTemplate = 'framework::backend/users';

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \Exception
     */
    public function index(): ResponseInterface
    {
        return view(
            template: $this->dashboardTemplate,
            data: [
                'title' => 'Dashboard'
            ]
        );
    }

    /**
     * @throws UnresolvableQueryHandlerException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function users(UserService $service): ResponseInterface
    {
        return view(
            template: $this->usersTemplate,
            data: [
                'title' => trans('User Management'),
                'users' => json_encode(array_values($service->findAll())),
                'roles' => UserRole::cases(),
            ]
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function store(ServerRequest $request, UserService $service): ResponseInterface
    {
        $service->createUser(
            StoreUserValidator::make($request)
        );

        return $this->redirect(
            url: $this->router->url(
                name: 'admin.users'
            )
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function edit(ServerRequest $request, UserService $service): ResponseInterface
    {
        $service->updateUser(
            UpdateUserValidator::make($request)
        );

        return $this->redirect(
            url: $this->router->url(
                name: 'admin.users'
            )
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws \ReflectionException
     * @throws NamedRouteNotFoundException
     */
    public function destroy(ServerRequest $request, UserService $service): ResponseInterface
    {
        $service->deleteUser(
            DestroyUserValidator::make($request)
        );

        return $this->redirect(
            url: $this->router->url(
                name: 'admin.users'
            )
        );
    }
}
