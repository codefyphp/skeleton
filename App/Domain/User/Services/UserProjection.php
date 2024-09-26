<?php

declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Domain\User\EmailAddressWasUpdate;
use App\Domain\User\Event\EmailAddressWasChanged;
use App\Domain\User\Event\NameWasChanged;
use App\Domain\User\Event\PasswordWasChanged;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\NameWasUpdate;
use Codefy\Domain\EventSourcing\Projection;

interface UserProjection extends Projection
{
    /**
     * Projects when a user was created.
     *
     * @param UserWasCreated $event
     * @return void
     */
    public function projectWhenUserWasCreated(UserWasCreated $event): void;

    /**
     * Projects when email address was changed.
     *
     * @param EmailAddressWasUpdate $event
     * @return void
     */
    public function projectWhenEmailAddressWasChanged(EmailAddressWasChanged $event): void;

    /**
     * Projects when name was changed.
     *
     * @param NameWasUpdate $event
     * @return void
     */
    public function projectWhenNameWasChanged(NameWasChanged $event): void;

    /**
     * Projects when password was changed.
     *
     * @param PasswordWasChanged $event
     * @return void
     */
    public function projectWhenPasswordWasChanged(PasswordWasChanged $event): void;
}
