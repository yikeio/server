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
        Schema::create('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('conversation_id');
            $table->string('role', 30);
            $table->text('content')->nullable();
            $table->json('raws')->nullable();
            $table->unsignedInteger('tokens_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->index(['conversation_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
