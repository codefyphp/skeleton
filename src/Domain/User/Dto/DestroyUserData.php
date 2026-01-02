<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Domain\User\Request\UserRequest;
use Domain\User\ValueObject\UserId;
use Exception;

final readonly class DestroyUserData
{
    public function __construct(
        public ?UserId $userId = null,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromRequest(UserRequest $request): self
    {
        return new self(
            userId: UserId::fromString($request->value('user_id')),
        );
    }
}
