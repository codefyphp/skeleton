<?php

declare(strict_types=1);

namespace Domain\User\Request;

use Codefy\Framework\Dto\Attribute\UseDto;
use Codefy\Framework\Dto\HasDto;
use Codefy\Framework\Dto\Trait\DtoAware;
use Codefy\Framework\Http\Request\FormRequest;
use Domain\User\Dto\DestroyUserData;
use Exception;

use function Codefy\Framework\Helpers\gate;
use function strtolower;

#[UseDto(DestroyUserData::class)]
final class DestroyUserRequest extends FormRequest implements HasDto
{
    use DtoAware;

    public function authorize(): bool
    {
        $method = strtolower($this->getMethod());

        return match ($method) {
            'put', 'patch' => gate('admin:delete:user'),
        };
    }

    /**
     * @throws Exception
     */
    public function rules(): array
    {
        $method = strtolower($this->getMethod());

        return match ($method) {
            'put', 'patch' => $this->delete(),
        };
    }

    public function delete(): array
    {
        return [
            'user_id' => 'required|ulid',
        ];
    }
}
