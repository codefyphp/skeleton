<?php

declare(strict_types=1);

namespace Application\Service;

use Codefy\Framework\Proxy\Codefy;
use Visio\Contracts\AuthContract;

use function Codefy\Framework\Helpers\config;
use function Codefy\Framework\Helpers\gate;
use function Codefy\Framework\Helpers\site_url;
use function phpb_redirect;

class VisioAuth implements AuthContract
{

    /**
     * @inheritDoc
     */
    public function handleRequest(?string $action = null): void
    {
        if (phpb_in_module('auth')) {
            if ($this->isAuthenticated()) {
                phpb_redirect(phpb_url('website_manager'));
            } else {
                Codefy::$PHP->flash->error('Access denied');
                phpb_redirect(site_url('login'));
            }
        } elseif ($action === 'logout') {
            phpb_redirect(site_url('logout'));
        }
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticated(): bool
    {
        return gate()->isLoggedIn() && gate(permission: 'visio:manage');
    }

    /**
     * @inheritDoc
     */
    public function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            phpb_redirect(site_url(config(key: 'auth.login_route')));
            exit();
        }
    }

    /**
     * @inheritDoc
     */
    public function renderLoginForm(): void
    {
        return ;
    }
}
