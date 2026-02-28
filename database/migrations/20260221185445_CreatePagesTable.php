<?php

declare(strict_types=1);

use Qubus\Exception\Exception;
use Qubus\Expressive\Migration\Migration;
use Qubus\Expressive\Schema\CreateTable;

class CreatePagesTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(table: 'pages')) {
            $this->schema()
                ->create(table: 'pages', callback: function (CreateTable $table) {
                    $table->integer(name: 'id')->notNull()->autoIncrement();
                    $table->string(name: 'name')->notNull();
                    $table->string(name: 'show_in_nav')->notNull();
                    $table->integer(name: 'nav_position')->notNull();
                    $table->string(name: 'nav_type')->notNull();
                    $table->string(name: 'layout')->notNull();
                    $table->text(name: 'data')->size(value: 'big')->defaultValue(null);
                    $table->dateTime(name: 'created_at')->defaultValue(value: 'CURRENT_TIMESTAMP')->notNull();
                    $table->dateTime(name: 'updated_at')->defaultValue(value: 'CURRENT_TIMESTAMP')->notNull();
                });
        }
    }

    /**
     * Undo the migration
     * @throws Exception
     */
    public function down(): void
    {
        if ($this->schema()->hasTable(table: 'pages')) {
            $this->schema()->drop(table: 'pages');
        }
    }
}
