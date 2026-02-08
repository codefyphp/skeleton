<?php

declare(strict_types=1);

namespace Application\Http\Controller;

use Codefy\Framework\Http\BaseController;
use Domain\User\Enum\UserRole;
use Domain\User\Validator\UpdateUserPasswordValidator;
use Domain\User\Validator\UpdateUserValidator;
use Domain\User\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Qubus\Http\ServerRequest;
use Qubus\Routing\Exceptions\NamedRouteNotFoundException;
use Qubus\Routing\Exceptions\RouteParamFailedConstraintException;

use function Codefy\Framework\Helpers\trans;
use function Codefy\Framework\Helpers\user;
use function Codefy\Framework\Helpers\view;

final class ProfileController extends BaseController
{
    private string $profileTemplate = 'framework::backend/profile';

    /**
     * @throws RouteParamFailedConstraintException
     * @throws NamedRouteNotFoundException
     * @throws \ReflectionException
     * @throws \Exception
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
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function update(
        ServerRequest $request,
        UserService $service
    ): ResponseInterface {
        if (!empty($request->get('password'))) {
            $service->updatePassword(
                UpdateUserPasswordValidator::make($request)
            );

            return $this->redirect(
                url: $this->router->url(
                    name: 'auth.logout'
                )
            );
        } else {
            $service->updateUser(
                UpdateUserValidator::make($request)
            );
        }

        return $this->redirect(
            url: $this->router->url(
                name: 'admin.profile'
            )
        );
    }
}
