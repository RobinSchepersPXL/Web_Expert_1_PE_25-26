<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {

            // Voeg enkel toe wat meestal ontbreekt (op basis van jouw errors)
            if (!Schema::hasColumn('events', 'start_date')) {
                $table->dateTime('start_date')->nullable();
            }

            if (!Schema::hasColumn('events', 'end_date')) {
                $table->dateTime('end_date')->nullable();
            }

            if (!Schema::hasColumn('events', 'capacity')) {
                $table->unsignedInteger('capacity')->nullable();
            }

            if (!Schema::hasColumn('events', 'price')) {
                $table->decimal('price', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('events', 'images')) {
                $table->json('images')->nullable();
            }

            if (!Schema::hasColumn('events', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {

            if (Schema::hasColumn('events', 'images')) {
                $table->dropColumn('images');
            }

            if (Schema::hasColumn('events', 'price')) {
                $table->dropColumn('price');
            }

            if (Schema::hasColumn('events', 'capacity')) {
                $table->dropColumn('capacity');
            }

            if (Schema::hasColumn('events', 'end_date')) {
                $table->dropColumn('end_date');
            }

            if (Schema::hasColumn('events', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('events', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
