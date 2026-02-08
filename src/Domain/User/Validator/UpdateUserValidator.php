<?php

declare(strict_types=1);

namespace Domain\User\Validator;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Validation\HttpInputValidator;
use Domain\User\Dto\UpdateUserData;
use Domain\User\Enum\UserRole;

use function Codefy\Framework\Helpers\gate;
use function implode;
use function strtolower;

#[UseDto(UpdateUserData::class)]
final class UpdateUserValidator extends HttpInputValidator implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        $method = strtolower($this->request->getMethod());

        return match ($method) {
            'put', 'patch' => (bool) gate('admin:edit:user'),
            default => false,
        };
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    public function rules(): array
    {
        $method = strtolower($this->request->getMethod());

        return match ($method) {
            'put', 'patch' => $this->update(),
            default => [],
        };
    }

    /**
     * @return array<string, string>
     * @throws \Exception
     */
    private function update(): array
    {
        $roles = implode(separator: ',', array: UserRole::values());

        return [
            'user_id' => 'required|ulid',
            'first_name' => 'required|string|min:3',
            'middle_name' => 'string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email',
            'role' => 'required|string|in:' . $roles,
        ];
    }
}
