<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Codefy\Framework\Http\BaseController;
use Codefy\QueryBus\UnresolvableQueryHandlerException;
use Domain\User\Enum\UserRole;
use Domain\User\Request\DestroyUserRequest;
use Domain\User\Request\StoreUserRequest;
use Domain\User\Request\UpdateUserRequest;
use Domain\User\Service\UserService;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Qubus\Exception\Data\TypeException;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;
use ReflectionException;

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
     * @throws Exception
     */
    public function index(): ResponseInterface
    {
        return view(template: $this->dashboardTemplate, data: ['title' => 'Dashboard']);
    }

    /**
     * @throws UnresolvableQueryHandlerException
     * @throws ReflectionException
     * @throws Exception
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
     * @throws TypeException
     * @throws Exception
     * @throws ReflectionException
     */
    public function store(StoreUserRequest $request, UserService $service): ResponseInterface
    {
        $service->createUser($request);

        return $this->redirect(url: $this->router->url(name: 'admin.users'));
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws ReflectionException
     * @throws Exception
     */
    public function edit(UpdateUserRequest $request, UserService $service): ResponseInterface
    {
        $service->updateUser($request);

        return $this->redirect(url: $this->router->url(name: 'admin.users'));
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws ReflectionException
     * @throws TypeException
     * @throws NamedRouteNotFoundException
     */
    public function destroy(DestroyUserRequest $request, UserService $service): ResponseInterface
    {
        $service->deleteUser($request);

        return $this->redirect(url: $this->router->url(name: 'admin.users'));
    }
}
