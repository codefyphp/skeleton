<?php

declare(strict_types=1);

namespace Application\Provider;

use Codefy\Framework\Auth\Auth;
use Codefy\Framework\Auth\Rbac\Rbac;
use Codefy\Framework\Auth\Rbac\RbacLoader;
use Codefy\Framework\Auth\Rbac\Resource\FileResource;
use Codefy\Framework\Auth\Rbac\Resource\StorageResource;
use Codefy\Framework\Auth\Repository\AuthUserRepository;
use Codefy\Framework\Auth\Sentinel;
use Codefy\Framework\Support\CodefyServiceProvider;
use Gettext\Translator;
use Gettext\TranslatorFunctions;
use Infrastructure\Persistence\Repository\PdoAuthUserRespository;
use Qubus\Exception\Exception;

final class RbacServiceProvider extends CodefyServiceProvider
{
    /**
     * @throws Exception
     */
    public function register(): void
    {
        $translator = new Translator();
        TranslatorFunctions::register($translator);

        $this->codefy->alias(original: StorageResource::class, alias: FileResource::class);
        $this->codefy->define(name: FileResource::class, args: [
            ':file' => 'rbac.json'
        ]);

        $this->codefy->share(nameOrInstance: Rbac::class);

        /** @var RbacLoader $loader */
        $loader = $this->codefy->make(name: RbacLoader::class);
        $loader->initRbacPermissions();
        $loader->initRbacRoles();

        $this->codefy->alias(original: Sentinel::class, alias: Auth::class);
        $this->codefy->share(nameOrInstance: Sentinel::class);
        $this->codefy->alias(original: AuthUserRepository::class, alias: PdoAuthUserRespository::class);
        $this->codefy->share(nameOrInstance: AuthUserRepository::class);
    }
}
