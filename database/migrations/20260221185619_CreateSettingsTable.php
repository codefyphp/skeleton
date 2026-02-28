<?php

declare(strict_types=1);

use Qubus\Exception\Exception;
use Qubus\Expressive\Migration\Migration;
use Qubus\Expressive\Schema\CreateTable;

class CreateSettingsTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(table: 'settings')) {
            $this->schema()
                ->create(table: 'settings', callback: function (CreateTable $table) {
                    $table->integer(name: 'id')->notNull()->autoIncrement();
                    $table->string(name: 'setting', length: 50)->notNull()->unique();
                    $table->text(name: 'value')->size(value: 'medium')->notNull();
                    $table->integer(name: 'is_array')->notNull();
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
        if ($this->schema()->hasTable(table: 'settings')) {
            $this->schema()->drop(table: 'settings');
        }
    }
}
