<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Support\Password;
use Codefy\Framework\Validation\DataValidator;
use Domain\User\ValueObject\Username;
use Domain\User\ValueObject\UserRole;
use Domain\User\ValueObject\UserToken;
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
     * @throws \Exception
     */
    public static function fromValidatedData(DataValidator $data): self
    {
        return new self(
            username: new Username($data->string(key: 'username')),
            token: new UserToken(),
            firstName: new StringLiteral($data->string(key: 'first_name')),
            middleName: new StringLiteral($data->string(key: 'middle_name', default: '')),
            lastName: new StringLiteral($data->string(key: 'last_name')),
            email: new EmailAddress($data->string(key: 'email')),
            role: new UserRole($data->string(key: 'role')),
            password: new StringLiteral(Password::hash($data->string(key: 'password'))),
        );
    }
}
