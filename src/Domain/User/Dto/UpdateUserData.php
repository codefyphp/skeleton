<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Validation\DataValidator;
use Domain\User\ValueObject\UserId;
use Domain\User\ValueObject\UserRole;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final readonly class UpdateUserData implements DataTransformer
{
    private function __construct(
        public UserId $userId,
        public StringLiteral $firstName,
        public StringLiteral $middleName,
        public StringLiteral $lastName,
        public EmailAddress $email,
        public UserRole $role,
    ) {
    }

    /**
     * @throws \Exception
     */
    public static function fromValidatedData(DataValidator $data): self
    {
        return new self(
            userId: UserId::fromString($data->value('user_id')),
            firstName: new StringLiteral($data->value('first_name')),
            middleName: new StringLiteral($data->value(value: 'middle_name', default: '')),
            lastName: new StringLiteral($data->value('last_name')),
            email: new EmailAddress($data->value('email')),
            role: new UserRole($data->value('role')),
        );
    }
}
