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
            $table->id();
            $table->bigInteger('branch_id')->nullable();
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('phone', 25);
            $table->string('password');
            $table->enum('role', ['owner', 'manager', 'admin', 'pic', 'security']);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
