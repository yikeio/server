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
        Schema::create('quota_statements', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('quota_id');
            $table->unsignedBigInteger('tokens_count');
            $table->string('tokenizable_type', 30);
            $table->unsignedBigInteger('tokenizable_id');
            $table->timestamps();

            $table->primary('id');
            $table->index('quota_id');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('quota_id')->default(0);
        });

        Schema::table('quotas', function (Blueprint $table) {
            $table->dropColumn('meter');
        });

        Schema::table('quotas', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'type', 'is_available']);
            $table->dropColumn('type');
        });

        Schema::table('quotas', function (Blueprint $table) {
            $table->index(['user_id', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quota_statements');

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('quota_id');
        });

        Schema::table('quotas', function (Blueprint $table) {
            $table->string('meter', 30)->nullable();
        });

        Schema::table('quotas', function (Blueprint $table) {
            $table->string('type', 30)->nullable();
            $table->index(['user_id', 'type', 'is_available']);
        });

        Schema::table('quotas', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_available']);
        });
    }
};
