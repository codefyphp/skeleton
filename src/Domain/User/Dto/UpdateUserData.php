<?php

declare(strict_types=1);

namespace Domain\User\Dto;

use Codefy\Framework\Dto\DataTransformer;
use Codefy\Framework\Http\Request\DataTransformerRequest;
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
    public static function fromRequest(DataTransformerRequest $request): self
    {
        return new self(
            userId: UserId::fromString($request->value('user_id')),
            firstName: new StringLiteral($request->value('first_name')),
            middleName: new StringLiteral($request->value(value: 'middle_name', default: '')),
            lastName: new StringLiteral($request->value('last_name')),
            email: new EmailAddress($request->value('email')),
            role: new StringLiteral($request->value('role')),
        );
    }
}
