<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // role: admin or user
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');

            // basic profile
            $table->string('first_name')->nullable()->after('role');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('last_name');
            $table->date('birthdate')->nullable()->after('phone');
            $table->string('address')->nullable()->after('birthdate');
            $table->text('bio')->nullable()->after('address');

            // profile photo path (Laravel convention)
            $table->string('profile_photo_path', 2048)->nullable()->after('bio');

            // locale / timezone and status
            $table->string('locale', 10)->nullable()->after('profile_photo_path');
            $table->string('timezone', 100)->nullable()->after('locale');
            $table->boolean('is_active')->default(true)->after('timezone');

            // last login timestamp
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'first_name',
                'last_name',
                'phone',
                'birthdate',
                'address',
                'bio',
                'profile_photo_path',
                'locale',
                'timezone',
                'is_active',
                'last_login_at',
            ]);
        });
    }
}
