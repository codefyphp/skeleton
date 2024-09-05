<?php

declare(strict_types=1);

use Codefy\Framework\Migration\Migration;
use Qubus\Dbal\Schema\CreateTable;
use Qubus\Exception\Exception;

class CreateUsersTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(table: 'users')) {
            $this->schema()
                ->create(table: 'users', callback: function (CreateTable $table) {
                    $table->string(name: 'user_id', length: 36)
                        ->primary()
                        ->unique(name: 'userId');
                    $table->string(name: 'username', length: 191)
                        ->unique(name: 'username')
                        ->notNull();
                    $table->string(name: 'first_name', length: 191);
                    $table->string(name: 'middle_name', length: 191);
                    $table->string(name: 'last_name', length: 191);
                    $table->string(name: 'email', length: 191)
                        ->unique(name: 'email')
                        ->notNull();
                    $table->string(name: 'password', length: 191)
                        ->notNull();
                    /*$table->string(name: 'confirmation_token', length: 191)
                        ->unique(name: 'confirmation_token')
                        ->notNull();*/
                    $table->dateTime(name: 'created_on');
                });
        }
    }

    /**
     * Undo the migration
     * @throws Exception
     */
    public function down(): void
    {
        if ($this->schema()->hasTable(table: 'users')) {
            $this->schema()->drop(table: 'users');
        }
    }
}
