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
        Schema::create('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('creator_id')->default(0);
            $table->decimal('amount', 10)->default(0);
            $table->string('number', 30);
            $table->string('state', 30);
            $table->string('title', 30);
            $table->json('processors')->nullable();
            $table->json('context')->nullable();
            $table->string('gateway', 30);
            $table->string('gateway_number', 30);
            $table->json('raws')->nullable();
            $table->timestamps();
            $table->timestamp('expired_at')->nullable();

            $table->primary('id');
            $table->index(['creator_id', 'state']);
            $table->unique('number');
            $table->unique(['gateway', 'gateway_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
