<?php

declare(strict_types=1);

namespace Domain\User\Service;

use Codefy\Domain\EventSourcing\Projection;
use Domain\User\Event\EmailAddressWasChanged;
use Domain\User\Event\NameWasChanged;
use Domain\User\Event\PasswordWasChanged;
use Domain\User\Event\UserWasCreated;

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
     * @param EmailAddressWasChanged $event
     * @return void
     */
    public function projectWhenEmailAddressWasChanged(EmailAddressWasChanged $event): void;

    /**
     * Projects when name was changed.
     *
     * @param NameWasChanged $event
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
