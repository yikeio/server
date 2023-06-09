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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('from_user_id')->default(0)->index();
            $table->unsignedBigInteger('payment_id')->default(0)->index();
            $table->unsignedInteger('amount')->default(0);
            $table->unsignedInteger('rate')->default(0)->comment('分成比例');
            $table->string('state')->default('pending')->index()->comment('unwithdrawn/withdrawn');
            $table->timestamp('withdrawn_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
