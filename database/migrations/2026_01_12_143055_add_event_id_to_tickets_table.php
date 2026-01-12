<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'event_id')) {
                $table->foreignId('event_id')
                    ->after('id')
                    ->constrained('events')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'event_id')) {
                $table->dropConstrainedForeignId('event_id');
            }
        });
    }
};
