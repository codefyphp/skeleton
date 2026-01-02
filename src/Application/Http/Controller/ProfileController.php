<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Codefy\Framework\Http\BaseController;
use Domain\User\Enum\UserRole;
use Domain\User\Request\UpdateUserPasswordRequest;
use Domain\User\Request\UpdateUserRequest;
use Domain\User\Service\UserService;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Qubus\Exception\Data\TypeException;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;
use ReflectionException;

use function Codefy\Framework\Helpers\trans;
use function Codefy\Framework\Helpers\user;
use function Codefy\Framework\Helpers\view;

final class ProfileController extends BaseController
{
    private string $profileTemplate = 'framework::backend/profile';

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws ReflectionException
     * @throws TypeException
     * @throws Exception
     */
    public function index(): ResponseInterface
    {
        return view(
            template: $this->profileTemplate,
            data: [
                'title' => trans('User Profile'),
                'user' => user(),
                'roles' => UserRole::cases(),
                'url' => $this->router->url(name: 'admin.profile.update'),
            ]
        );
    }

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws Exception
     * @throws ReflectionException
     */
    public function update(
        UpdateUserRequest $userRequest,
        UpdateUserPasswordRequest $passwordRequest,
        UserService $service
    ): ResponseInterface {
        if (!empty($passwordRequest->get('password'))) {
            $service->updatePassword($passwordRequest);
            return $this->redirect(
                url: $this->router->url(
                    name: 'auth.logout'
                )
            );
        } else {
            $service->updateUser($userRequest);
        }

        return $this->redirect(
            url: $this->router->url(
                name: 'admin.profile'
            )
        );
    }
}
