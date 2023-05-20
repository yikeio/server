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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('email')->nullable()->after('name')->unique();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone_number')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email']);
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['email']);
        });
    }
};
