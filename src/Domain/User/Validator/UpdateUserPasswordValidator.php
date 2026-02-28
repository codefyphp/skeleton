<?php

declare(strict_types=1);

namespace Domain\User\Validator;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Proxy\Codefy;
use Codefy\Framework\Validation\HttpInputValidator;
use Domain\User\Dto\UpdateUserPassword;

use function Codefy\Framework\Helpers\gate;
use function strtolower;

#[UseDto(UpdateUserPassword::class)]
final class UpdateUserPasswordValidator extends HttpInputValidator implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        $method = strtolower($this->request->getMethod());

        return match ($method) {
            'put', 'patch' => (bool) gate('admin:profile'),
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
        $passwordMinLength = Codefy::$PHP->configContainer->integer(key: 'auth.password_min_length');

        return [
            'password' => "required|string|min:{$passwordMinLength}",
            'confirm_password' => 'same:password',
        ];
    }
}
