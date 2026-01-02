<?php

declare(strict_types=1);

namespace Domain\User\Request;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Http\Request\FormRequest;
use Codefy\Framework\Proxy\Codefy;
use Domain\User\Dto\UpdateUserPassword;
use Exception;

use function Codefy\Framework\Helpers\gate;
use function strtolower;

#[UseDto(UpdateUserPassword::class)]
final class UserRequest extends FormRequest implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        $method = strtolower($this->getMethod());

        return match ($method) {
            'post' => gate('admin:create:user'),
            'put', 'patch', 'delete' => gate('admin:edit:user'),
        };
    }

    /**
     * @throws Exception
     */
    public function rules(): array
    {
        $method = strtolower($this->getMethod());

        return match ($method) {
            'post' => $this->store(),
            'put', 'patch' => $this->update(),
            'delete' => $this->delete(),
        };
    }

    /**
     * @throws Exception
     */
    private function store(): array
    {
        $usernameMinLength = Codefy::$PHP->configContainer->getConfigKey('auth.username_min_length');
        $passwordMinLength = Codefy::$PHP->configContainer->getConfigKey('auth.password_min_length');

        return [
            'username' => "required|string|min:{$usernameMinLength}|max:80",
            'first_name' => 'required|string|min:3',
            'middle_name' => 'string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email',
            'role' => 'required|string',
            'password' => "required|string|min:{$passwordMinLength}",
        ];
    }

    /**
     * @throws Exception
     */
    private function update(): array
    {
        $passwordMinLength = Codefy::$PHP->configContainer->getConfigKey('auth.password_min_length');
        $passwordCheck = $this->get('password') ?? '';

        if (!empty($passwordCheck)) {
            return [
                'password' => "required|string|min:{$passwordMinLength}",
                'confirm_password' => 'same:password',
            ];
        }

        return [
            'user_id' => 'required|ulid',
            'first_name' => 'required|string|min:3',
            'middle_name' => 'string|min:3',
            'last_name' => 'required|string|min:3',
            'email' => 'required|email',
            'role' => 'required|string',
        ];
    }

    public function delete(): array
    {
        return [
            'user_id' => 'required|ulid',
        ];
    }
}
