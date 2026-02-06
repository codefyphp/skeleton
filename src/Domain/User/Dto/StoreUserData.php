<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Support\Password;
use Codefy\Framework\Validation\DataValidator;
use Domain\User\ValueObject\Username;
use Domain\User\ValueObject\UserRole;
use Domain\User\ValueObject\UserToken;
use Exception;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final readonly class StoreUserData implements DataTransformer
{
    private function __construct(
        public Username $username,
        public UserToken $token,
        public StringLiteral $firstName,
        public StringLiteral $middleName,
        public StringLiteral $lastName,
        public EmailAddress $email,
        public UserRole $role,
        public StringLiteral $password,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromValidatedData(DataValidator $data): self
    {
        return new self(
            username: new Username($data->value(value: 'username')),
            token: new UserToken(),
            firstName: new StringLiteral($data->value(value: 'first_name')),
            middleName: new StringLiteral($data->value(value: 'middle_name', default: '')),
            lastName: new StringLiteral($data->value(value: 'last_name')),
            email: new EmailAddress($data->value(value: 'email')),
            role: new UserRole($data->value(value: 'role')),
            password: new StringLiteral(Password::hash($data->value(value: 'password'))),
        );
    }
}
