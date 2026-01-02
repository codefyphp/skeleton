<?php

declare(strict_types=1);

namespace Domain\User\Request;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Http\Request\FormRequest;
use Domain\User\Dto\UpdateUserData;
use Exception;

use function Codefy\Framework\Helpers\gate;
use function Codefy\Framework\Helpers\get_system_roles;
use function implode;
use function strtolower;

#[UseDto(UpdateUserData::class)]
final class UpdateUserRequest extends FormRequest implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        $method = strtolower($this->getMethod());

        return match ($method) {
            'put', 'patch' => gate('admin:edit:user'),
        };
    }

    /**
     * @throws Exception
     */
    public function rules(): array
    {
        $method = strtolower($this->getMethod());

        return match ($method) {
            'put', 'patch' => $this->update(),
        };
    }

    /**
     * @throws Exception
     */
    private function update(): array
    {
        $roles = implode(separator: ',', array: get_system_roles());

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
