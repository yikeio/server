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
        Schema::create('profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('platform', 50);
            $table->string('open_id', 50);
            $table->string('nickname', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('avatar')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
            $table->index(['user_id', 'deleted_at']);
            $table->index(['platform', 'open_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number', 30)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number', 30)->change();
        });
    }
};
