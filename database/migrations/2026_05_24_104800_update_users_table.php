<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid()->unique()->after('id');
            $table->enum('role', ['employer', 'candidate'])->after('name');
            $table->string('username')->unique()->after('email_verified_at');
            $table->json('profile_data')->nullable()->after('remember_token');
            $table->string('logo')->nullable()->after('profile_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid', 'role', 'username', 'profile_data', 'logo');
        });
    }
};
