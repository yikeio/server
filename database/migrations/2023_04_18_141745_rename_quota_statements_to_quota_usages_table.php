<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('quota_statements', 'quota_usages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('quota_usages', 'quota_statements');
    }
};
