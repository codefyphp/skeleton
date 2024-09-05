<?php

declare(strict_types=1);

namespace App\Domain\User\Services;

use Qubus\Http\Session\SessionEntity;

class UserSession implements SessionEntity
{
    public ?string $userId = null;

    public ?string $email = null;

    public function withId(?string $userId = null): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function withEmail(?string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    public function userId(): string|null
    {
        return $this->userId;
    }

    public function email(): string|null
    {
        return $this->email;
    }

    public function clear(): void
    {
        if(!empty($this->userId)) {
            unset($this->userId);
        }

        if(!empty($this->email)) {
            unset($this->email);
        }
    }

    public function isEmpty(): bool
    {
        return empty($this->userId) && empty($this->email);
    }
}
