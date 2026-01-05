<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Support\Password;
use Codefy\Framework\Validation\DataValidator;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\UserToken;
use Exception;
use Qubus\ValueObjects\StringLiteral\StringLiteral;

final readonly class UpdateUserPassword implements DataTransformer
{
    private function __construct(
        public ?UserId $userId = null,
        public ?StringLiteral $password = null,
        public ?UserToken $token = null,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromValidatedData(DataValidator $data): self
    {
        return new self(
            userId: UserId::fromString($data->value('user_id')),
            password: new StringLiteral(Password::hash($data->value('password'))),
            token: new UserToken(),
        );
    }
}
