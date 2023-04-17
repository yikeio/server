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
        Schema::table('quotas', function (Blueprint $table) {
            $table->unsignedBigInteger('tokens_count')->default(0);
            $table->unsignedBigInteger('used_tokens_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotas', function (Blueprint $table) {
            $table->dropColumn('tokens_count');
            $table->dropColumn('used_tokens_count');
        });
    }
};
