<?php

declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Domain\User\Query\FindUserByIdQuery;
use Codefy\CommandBus\Containers\ContainerFactory;
use Codefy\QueryBus\Busses\SynchronousQueryBus;
use Codefy\QueryBus\Enquire;
use Codefy\QueryBus\Resolvers\NativeQueryHandlerResolver;
use Psr\Http\Message\ServerRequestInterface;
use Qubus\Http\Session\SessionService;

use function Qubus\Support\Helpers\is_false__;

final class UserAuth
{
    private static ?string $userId = null;

    public static function isAuthenticated(SessionService $sessionService, ServerRequestInterface $request): bool
    {
        $sessionService::$options = [
            'cookie-name' => 'USERSESSID'
        ];

        $request = $request->withCookieParams($_COOKIE);
        $session = $sessionService->makeSession($request);

        /** @var UserSession $userSession */
        $userSession = $session->get(UserSession::class);

        $resolver = new NativeQueryHandlerResolver(container: ContainerFactory::make(config: []));
        $enquirer = new Enquire(bus: new SynchronousQueryBus($resolver));

        $query = new FindUserByIdQuery(data: [
            'userId' => $userSession->userId() ?? '',
        ]);

        $user = $enquirer->execute($query);

        if(is_false__($user)) {
            return false;
        }

        static::$userId = $userSession->userId();

        return true;
    }

    public static function currentUserId(): ?string
    {
        return static::$userId;
    }

    public static function findUserById()
    {
        $resolver = new NativeQueryHandlerResolver(container: ContainerFactory::make(config: []));
        $enquirer = new Enquire(bus: new SynchronousQueryBus($resolver));

        $query = new FindUserByIdQuery(data: [
            'userId' => static::currentUserId() ?? '',
        ]);

        $user = $enquirer->execute($query);

        return $user;
    }
}
