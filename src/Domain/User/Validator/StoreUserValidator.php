<?php

declare(strict_types=1);

namespace Domain\User\Validator;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Validation\HttpInputValidator;
use Domain\User\Dto\StoreUserData;
use Domain\User\Enum\UserRole;

use function Codefy\Framework\Helpers\gate;
use function implode;

#[UseDto(StoreUserData::class)]
final class StoreUserValidator extends HttpInputValidator implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        return (bool) gate('admin:create:user');
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    public function rules(): array
    {
        $roles = implode(separator: ',', array: UserRole::values());

        return [
            'first_name' => 'required|string|min:3',
            'middle_name' => 'string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email',
            'role' => 'required|string|in:' . $roles,
        ];
    }
}
