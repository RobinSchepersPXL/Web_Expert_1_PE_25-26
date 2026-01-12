<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'prijs')) {
                $table->decimal('prijs', 8, 2)->after('event_id');
            }

            if (!Schema::hasColumn('tickets', 'beschikbare_aantal')) {
                $table->unsignedInteger('beschikbare_aantal')->after('prijs');
            }

            if (!Schema::hasColumn('tickets', 'gereserveerd_aantal')) {
                $table->unsignedInteger('gereserveerd_aantal')->default(0)->after('beschikbare_aantal');
            }

            if (!Schema::hasColumn('tickets', 'categorie')) {
                $table->string('categorie')->nullable()->after('gereserveerd_aantal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'categorie')) {
                $table->dropColumn('categorie');
            }
            if (Schema::hasColumn('tickets', 'gereserveerd_aantal')) {
                $table->dropColumn('gereserveerd_aantal');
            }
            if (Schema::hasColumn('tickets', 'beschikbare_aantal')) {
                $table->dropColumn('beschikbare_aantal');
            }
            if (Schema::hasColumn('tickets', 'prijs')) {
                $table->dropColumn('prijs');
            }
        });
    }
};
