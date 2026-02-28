<?php

declare(strict_types=1);

namespace Database\Seeders;

use Codefy\Framework\Support\Password;
use Domain\User\Command\CreateUserCommand;
use Domain\User\Enum\UserRole;
use Domain\User\ValueObject\Username;
use Domain\User\ValueObject\UserToken;
use Exception;
use Qubus\Expressive\Migration\Seeder\BaseSeeder;
use Qubus\Expressive\Migration\Seeder\SeederContext;
use Qubus\ValueObjects\StringLiteral\StringLiteral;
use Qubus\ValueObjects\Web\EmailAddress;
use RuntimeException;

use function Codefy\Framework\Helpers\command;

class UserSeeder extends BaseSeeder
{
    /**
     * @throws Exception
     */
    public function run(SeederContext $context): void
    {
        if ($context->isProduction) {
            throw new RuntimeException('Seeding users in production is disabled.');
        }

        $this->withFakerSeed(12345678);
        $username = [
            'hschimmel',
            'marksevans',
            'iabbott',
            'ffay',
            'rayray',
            'zoielife',
            'xwintheiser',
            'rutherford',
            'nkohler',
            'esperanza',
        ];

        for ($i = 0; $i < 10; $i++) {
            $command = new CreateUserCommand([
                'username'   => new Username($this->faker->unique()->randomElement($username)),
                'firstName' => new StringLiteral($this->faker->firstName()),
                'middleName' => new StringLiteral(''),
                'lastName'  => new StringLiteral($this->faker->lastName()),
                'email'      => new EmailAddress($this->faker->unique()->safeEmail()),
                'password'   => new StringLiteral(Password::hash(password: 'tUB2sQoPuuX*3pycL0HGYMs2#!')),
                'token' => new UserToken(),
                'role' => new \Domain\User\ValueObject\UserRole($this->faker->randomElement(UserRole::cases())->value)
            ]);

            command($command);
        }
    }
}
