<?php
// File: database/migrations/2026_01_07_115429_ensure_password_resets_has_email.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsurePasswordResetsHasEmail extends Migration
{
    public function up()
    {
        // If table doesn't exist, create it with expected columns
        if (! Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
            return;
        }

        // If table exists but column is missing, add it
        if (! Schema::hasColumn('password_resets', 'email')) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->string('email')->index()->after('id')->nullable(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('password_resets') && Schema::hasColumn('password_resets', 'email')) {
            Schema::table('password_resets', function (Blueprint $table) {
                // drop index first then column
                $table->dropIndex(['email']);
                $table->dropColumn('email');
            });
        }
    }
}
