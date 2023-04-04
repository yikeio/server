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
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('root_referrer_id')->default(0);
            $table->unsignedBigInteger('referrer_id')->default(0);
            $table->unsignedInteger('level')->default(0);
            $table->string('referrer_path', 255)->nullable();
            $table->string('referral_code', 10)->nullable();
            $table->unsignedInteger('referrals_count')->default(0);
            $table->string('name', 50)->nullable();
            $table->string('phone_number', 30);
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
            $table->timestamp('first_active_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->softDeletes();

            $table->primary('id');
            $table->index(['root_referrer_id', 'deleted_at']);
            $table->index(['referrer_id', 'deleted_at']);
            $table->index(['referrer_path', 'deleted_at']);
            $table->unique('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
