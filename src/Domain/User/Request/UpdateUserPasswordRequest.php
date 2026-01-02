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
final class UpdateUserPasswordRequest extends FormRequest implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        $method = strtolower($this->getMethod());

        return match ($method) {
            'put', 'patch' => gate('admin:profile'),
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
        $passwordMinLength = Codefy::$PHP->configContainer->getConfigKey('auth.password_min_length');

        return [
            'password' => "required|string|min:{$passwordMinLength}",
            'confirm_password' => 'same:password',
        ];
    }
}
