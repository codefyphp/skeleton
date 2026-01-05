<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Validation\DataValidator;
use Domain\User\ValueObject\UserId;
use Exception;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final readonly class UpdateUserData implements DataTransformer
{
    private function __construct(
        public ?UserId $userId = null,
        public ?StringLiteral $firstName = null,
        public ?StringLiteral $middleName = null,
        public ?StringLiteral $lastName = null,
        public ?EmailAddress $email = null,
        public ?StringLiteral $role = null,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromValidatedData(DataValidator $data): self
    {
        return new self(
            userId: UserId::fromString($data->value('user_id')),
            firstName: new StringLiteral($data->value('first_name')),
            middleName: new StringLiteral($data->value(value: 'middle_name', default: '')),
            lastName: new StringLiteral($data->value('last_name')),
            email: new EmailAddress($data->value('email')),
            role: new StringLiteral($data->value('role')),
        );
    }
}
