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
            userId: UserId::fromString($data->string(key: 'user_id')),
            firstName: new StringLiteral($data->string(key: 'first_name')),
            middleName: new StringLiteral($data->string(key: 'middle_name', default: '')),
            lastName: new StringLiteral($data->string(key: 'last_name')),
            email: new EmailAddress($data->string(key: 'email')),
            role: new UserRole($data->string(key: 'role')),
        );
    }
}
