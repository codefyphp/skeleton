<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Validation\DataValidator;
use Domain\User\ValueObject\UserId;
use Exception;

final readonly class DestroyUserData implements DataTransformer
{
    public function __construct(
        public ?UserId $userId = null,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromValidatedData(DataValidator $data): self
    {
        return new self(
            userId: UserId::fromString($data->value('user_id')),
        );
    }
}
