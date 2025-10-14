<?php

declare(strict_types=1);

use Qubus\Exception\Exception;
use Qubus\Expressive\Migration\Migration;
use Qubus\Expressive\Schema\AlterTable;

class AddTokenField extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        $this->schema()->alter(table: 'users', callback: function (AlterTable $table): void {
            $table->string(name: 'token', length: 191)->notNull();
        });
    }

    /**
     * Undo the migration.
     *
     * @return void
     * @throws Exception
     */
    public function down(): void
    {
        $this->schema()->alter(table: 'users', callback: function (AlterTable $table) {
            $table->dropColumn(name: 'token');
        });
    }
}
