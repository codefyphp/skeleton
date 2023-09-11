<?php

declare(strict_types=1);

use Codefy\Framework\Migration\Migration;
use Qubus\Dbal\Schema\CreateTable;
use Qubus\Exception\Exception;

use function Codefy\Framework\Helpers\env;

class CreateUsersTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(env('DB_TABLE_PREFIX') . 'users')) {
            $this->schema()->create(env('DB_TABLE_PREFIX') . 'users', function (CreateTable $table) {
                $table->string('user_id', 36)->primary()->unique(env('DB_TABLE_PREFIX') . 'user');
                $table->string('fist_name', 191);
                $table->string('last_name', 191);
                $table->string('email', 191)->unique(env('DB_TABLE_PREFIX') . 'email')->notNull();
            });
        }
    }

    /**
     * Undo the migration
     * @throws Exception
     */
    public function down(): void
    {
        if ($this->schema()->hasTable(env('DB_TABLE_PREFIX') . 'users')) {
            $this->schema()->drop(env('DB_TABLE_PREFIX') . 'users');
        }
    }
}
