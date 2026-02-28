<?php

declare(strict_types=1);

use Qubus\Exception\Exception;
use Qubus\Expressive\Migration\Migration;
use Qubus\Expressive\Schema\CreateTable;

class CreatePageTranslationsTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(table: 'page_translations')) {
            $this->schema()
                ->create(table: 'page_translations', callback: function (CreateTable $table) {
                    $table->integer(name: 'id')->notNull()->autoIncrement();
                    $table->integer(name: 'page_id')->notNull();
                    $table->string(name: 'locale', length: 50)->notNull();
                    $table->string(name: 'title')->notNull();
                    $table->string(name: 'meta_title')->notNull();
                    $table->string(name: 'meta_description')->notNull();
                    $table->string(name: 'route')->notNull();
                    $table->dateTime(name: 'created_at')->defaultValue('CURRENT_TIMESTAMP')->notNull();
                    $table->dateTime(name: 'updated_at')->defaultValue('CURRENT_TIMESTAMP')->notNull();

                    $table->unique(['page_id', 'locale']);
                    $table->foreign(columns: 'page_id')
                        ->references('pages', 'id')
                        ->onUpdate(action: 'cascade')
                        ->onDelete(action: 'cascade');
                });
        }
    }

    /**
     * Undo the migration
     * @throws Exception
     */
    public function down(): void
    {
        if ($this->schema()->hasTable(table: 'page_translations')) {
            $this->schema()->drop(table: 'page_translations');
        }
    }
}
