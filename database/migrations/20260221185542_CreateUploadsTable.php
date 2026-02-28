<?php

declare(strict_types=1);

use Qubus\Exception\Exception;
use Qubus\Expressive\Migration\Migration;
use Qubus\Expressive\Schema\CreateTable;

class CreateUploadsTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(table: 'uploads')) {
            $this->schema()
                ->create(table: 'uploads', callback: function (CreateTable $table) {
                    $table->integer(name: 'id')->notNull()->autoIncrement();
                    $table->string(name: 'public_id', length: 50)->notNull()->unique();
                    $table->string(name: 'original_file', length: 512)->notNull();
                    $table->string(name: 'mime_type', length: 50)->notNull();
                    $table->string(name: 'server_file', length: 512)->notNull()->unique();
                    $table->dateTime(name: 'created_at')->defaultValue('CURRENT_TIMESTAMP')->notNull();
                    $table->dateTime(name: 'updated_at')->defaultValue('CURRENT_TIMESTAMP')->notNull();
                });
        }
    }

    /**
     * Undo the migration
     * @throws Exception
     */
    public function down(): void
    {
        if ($this->schema()->hasTable(table: 'uploads')) {
            $this->schema()->drop(table: 'uploads');
        }
    }
}
