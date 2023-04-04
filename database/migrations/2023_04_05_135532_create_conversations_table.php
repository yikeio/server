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
        Schema::create('conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('creator_id');
            $table->string('title')->nullable();
            $table->unsignedInteger('messages_count')->default(0);
            $table->unsignedInteger('tokens_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('first_active_at')->nullable();
            $table->timestamp('last_active_at')->nullable();

            $table->primary('id');
            $table->index(['creator_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
