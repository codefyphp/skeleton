<?php

declare(strict_types=1);

namespace Domain\User\Request;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Http\Request\FormRequest;
use Domain\User\Dto\StoreUserData;
use Exception;

use function Codefy\Framework\Helpers\gate;
use function Codefy\Framework\Helpers\get_system_roles;
use function implode;

#[UseDto(StoreUserData::class)]
final class StoreUserRequest extends FormRequest implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        return gate('admin:create:user');
    }

    /**
     * @throws Exception
     */
    public function rules(): array
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
