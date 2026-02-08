<?php

declare(strict_types=1);

namespace Domain\User\Validator;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Validation\HttpInputValidator;
use Domain\User\Dto\DestroyUserData;

use function Codefy\Framework\Helpers\gate;
use function strtolower;

#[UseDto(DestroyUserData::class)]
final class DestroyUserValidator extends HttpInputValidator implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        $method = strtolower($this->request->getMethod());

        return match ($method) {
            'put', 'patch' => (bool) gate('admin:delete:user'),
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
            'put', 'patch' => $this->delete(),
            default => [],
        };
    }

    /**
     * @return array<string, string>
     */
    public function delete(): array
    {
        return [
            'user_id' => 'required|ulid',
        ];
    }
}
