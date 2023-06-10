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
        Schema::create('prompts', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->startingValue(1);
            $table->unsignedBigInteger('creator_id')->default(0);
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->text('prompt_cn');
            $table->text('prompt_en')->nullable();
            $table->json('settings')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompts');
    }
};
