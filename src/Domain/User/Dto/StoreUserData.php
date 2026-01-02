<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Support\Password;
use Domain\User\Request\UserRequest;
use Domain\User\ValueObject\Username;
use Domain\User\ValueObject\UserToken;
use Exception;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;

final readonly class StoreUserData
{
    private function __construct(
        public ?Username $username = null,
        public ?UserToken $token = null,
        public ?StringLiteral $firstName = null,
        public ?StringLiteral $middleName = null,
        public ?StringLiteral $lastName = null,
        public ?EmailAddress $email = null,
        public ?StringLiteral $role = null,
        public ?StringLiteral $password = null,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromRequest(UserRequest $request): self
    {
        return new self(
            username: new Username($request->value(value: 'username')),
            token: new UserToken(),
            firstName: new StringLiteral($request->value(value: 'first_name')),
            middleName: new StringLiteral($request->value(value: 'middle_name', default: '')),
            lastName: new StringLiteral($request->value(value: 'last_name')),
            email: new EmailAddress($request->value(value: 'email')),
            role: new StringLiteral($request->value(value: 'role')),
            password: new StringLiteral(Password::hash($request->value(value: 'password'))),
        );
    }
}
