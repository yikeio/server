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
        Schema::create('quotas', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->boolean('is_available')->default(false);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('type', 30);
            $table->json('usage')->nullable();
            $table->timestamps();
            $table->timestamp('expired_at')->nullable();

            $table->primary('id');
            $table->unique(['user_id', 'type', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotas');
    }
};
