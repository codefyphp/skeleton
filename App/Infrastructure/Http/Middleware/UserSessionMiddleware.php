<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Middleware;

use App\Domain\User\Services\UserSession;
use Codefy\Framework\Codefy;
use Exception;
use PDOException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qubus\Config\ConfigContainer;
use Qubus\Exception\Data\TypeException;
use Qubus\Http\Session\SessionService;

use function Codefy\Framework\Helpers\orm;

class UserSessionMiddleware implements MiddlewareInterface
{
    public const SESSION_ATTRIBUTE = 'USER_SESSION';

    public function __construct(protected ConfigContainer $configContainer, protected SessionService $sessionService)
    {
    }

    /**
     * @inheritDoc
     * @throws TypeException
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        SessionService::$options = [
            'cookie-name' => 'USERSESSID',
            'cookie-lifetime' => (int) $this->configContainer->getConfigKey(key: 'cookies.lifetime', default: 86400),
        ];
        $session = $this->sessionService->makeSession($request);

        try {
            $db = orm()->setStructure('user_id');
            $userDb = $db->table('users')
                ->where('email = ?', $request->getParsedBody()['username'])->findOne();

            $password = password_verify($request->getParsedBody()['password'], $userDb->password ?? '');
        } catch(PDOException $ex) {
            Codefy::$PHP->flash->error('Unknown.');
        }

        if($password) {
            /** @var UserSession $user */
            $user = $session->get(type: UserSession::class);
            $user
                ->withId($userDb->user_id)
                ->withEmail($userDb->email);

            $request  = $request->withAttribute(self::SESSION_ATTRIBUTE, $session);

            Codefy::$PHP->flash->success('Login successful.');
        }

        $response = $handler->handle($request);

        return $this->sessionService->commitSession($response, $session);
    }
}
