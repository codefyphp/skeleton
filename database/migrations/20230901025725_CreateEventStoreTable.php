<?php

declare(strict_types=1);

use Codefy\Framework\Migration\Migration;
use Qubus\Dbal\Schema\CreateTable;
use Qubus\Exception\Exception;

use function Codefy\Framework\Helpers\env;

class CreateEventStoreTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(table: env(key: 'DB_TABLE_PREFIX') . 'event_store')) {
            $this->schema()->create(
                table: env(key: 'DB_TABLE_PREFIX') . 'event_store',
                callback: function (CreateTable $table) {
                    $table->string(
                        name: 'event_id',
                        length: 36
                    )->primary()->unique(name: env(key: 'DB_TABLE_PREFIX') . 'event');
                    $table->string(name: 'event_type', length: 191)->notNull();
                    $table->string(name: 'payload', length: 191);
                    $table->string(name: 'metadata', length: 191);
                    $table->string(name: 'aggregate_id', length: 191)->notNull();
                    $table->string(name: 'aggregate_type', length: 191)->notNull();
                    $table->integer(name: 'aggregate_playhead')->size(value: 'large')->notNull();
                    $table->dateTime(name: 'recorded_at');
                    $table->unique(
                        columns: ['aggregate_id','aggregate_type','aggregate_playhead'],
                        name: env(key: 'DB_TABLE_PREFIX') . 'aggregate'
                    );
                }
            );
        }
    }

    /**
     * Undo the migration
     * @throws Exception
     */
    public function down(): void
    {
        if ($this->schema()->hasTable(table: env(key: 'DB_TABLE_PREFIX') . 'event_store')) {
            $this->schema()->drop(table: env(key: 'DB_TABLE_PREFIX') . 'event_store');
        }
    }
}
