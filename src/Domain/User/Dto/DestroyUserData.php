<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Validation\DataValidator;
use Domain\User\ValueObject\UserId;

final readonly class DestroyUserData implements DataTransformer
{
    private function __construct(
        public UserId $userId,
    ) {
    }

    /**
     * @throws \Exception
     */
    public static function fromValidatedData(DataValidator $data): self
    {
        return new self(
            userId: UserId::fromString($data->string(key: 'user_id')),
        );
    }
}
