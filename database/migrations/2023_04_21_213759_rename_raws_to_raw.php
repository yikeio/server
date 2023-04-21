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
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('raws', 'raw');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('raws', 'raw');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('raw', 'raws');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('raw', 'raws');
        });
    }
};
