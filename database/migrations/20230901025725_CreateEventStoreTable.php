<?php

declare(strict_types=1);

use Codefy\Framework\Migration\Migration;
use Qubus\Dbal\Schema\CreateTable;
use Qubus\Exception\Exception;

class CreateEventStoreTable extends Migration
{
    /**
     * Do the migration
     * @throws Exception
     */
    public function up(): void
    {
        if (!$this->schema()->hasTable(table: 'event_store')) {
            $this->schema()->create(
                table: 'event_store',
                callback: function (CreateTable $table) {
                    $table->string(
                        name: 'event_id',
                        length: 36
                    )->primary()->unique(name: 'eventId');
                    $table->string(name: 'transaction_id', length: 36);
                    $table->string(name: 'event_type', length: 191)->notNull();
                    $table->string(name: 'event_classname', length: 191)->notNull();
                    $table->text(name: 'payload')->size(value: 'big')->notNull();
                    $table->text(name: 'metadata')->size(value: 'big')->notNull();
                    $table->string(name: 'aggregate_id', length: 36)->notNull();
                    $table->string(name: 'aggregate_type', length: 191)->notNull();
                    $table->integer(name: 'aggregate_playhead')->size(value: 'large')->notNull();
                    $table->dateTime(name: 'recorded_at')->notNull();
                    $table->unique(
                        columns: ['aggregate_id','aggregate_type','aggregate_playhead'],
                        name: 'domainEvent'
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
        if ($this->schema()->hasTable(table: 'event_store')) {
            $this->schema()->drop(table: 'event_store');
        }
    }
}
