<?php

declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Domain\User\Event\EmailAddressWasChanged;
use App\Domain\User\Event\NameWasChanged;
use App\Domain\User\Event\PasswordWasChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\UserProjection;
use Codefy\Domain\EventSourcing\BaseProjection;
use Codefy\Framework\Support\Password;
use Exception as NativeException;
use Qubus\Dbal\DB;
use Qubus\Expressive\OrmBuilder;
use Qubus\Expressive\OrmException;

use function Codefy\Framework\Helpers\config;

final class DatabaseUserProjection extends BaseProjection implements UserProjection
{
    private ?OrmBuilder $orm = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->orm = new OrmBuilder(
            DB::connection((array)config('database.connections.default'))
        );
    }

    public function projectWhenUserWasCreated(UserWasCreated $event): void
    {
        try {
            $this->orm->transactional(callback: function () use ($event) {
                $this->orm
                    ->table(tableName: 'users')
                    ->set([
                        'user_id' => $event->userId()->__toString(),
                        'username' => $event->username()->__toString(),
                        'first_name' => $event->name()->getFirstName()->toNative(),
                        'middle_name' => $event->name()->getMiddleName()->toNative(),
                        'last_name' => $event->name()->getLastName()->toNative(),
                        'email' => $event->emailAddress()->toNative(),
                        'password' => Password::hash($event->password()->toNative()),
                        'created_on' => $event->createdOn(),
                ])
                ->save();
            });
        } catch (OrmException $e) {
            throw new NativeException(message: $e->getMessage());
        }
    }

    public function projectWhenEmailAddressWasChanged(EmailAddressWasChanged $event): void
    {
        try {
            $this->orm->transactional(callback: function () use ($event) {
                $this->orm
                    ->table(tableName: 'users')
                    ->set([
                        'email' => $event->emailAddress()->toNative(),
                ])
                ->where('user_id = ?', $event->userId()->__toString())
                ->update();
            });
        } catch (OrmException $e) {
            throw new NativeException(message: $e->getMessage());
        }
    }

    public function projectWhenNameWasChanged(NameWasChanged $event): void
    {
        try {
            $this->orm->transactional(callback: function () use ($event) {
                $this->orm
                    ->table(tableName: 'users')
                    ->set([
                        'first_name' => $event->firstName(),
                        'middle_name' => $event->middleName(),
                        'last_name' => $event->lastName(),
                ])
                ->where('user_id = ?', $event->userId()->__toString())
                ->update();
            });
        } catch (OrmException $e) {
            throw new NativeException(message: $e->getMessage());
        }
    }

    public function projectWhenPasswordWasChanged(PasswordWasChanged $event): void
    {
        try {
            $this->orm->transactional(callback: function () use ($event) {
                $this->orm
                    ->table(tableName: 'users')
                    ->set([
                        'password' => Password::hash($event->password()->toNative()),
                ])
                ->where('user_id = ?', $event->userId()->__toString())
                ->update();
            });
        } catch (OrmException $e) {
            throw new NativeException(message: $e->getMessage());
        }
    }
}
