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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('guest_code', 30)->unique();
            $table->string('name', 150);
            $table->string('phone', 25)->index();
            $table->string('email', 150)->nullable();
            $table->string('company_name', 180)->nullable();
            $table->string('position', 100)->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('guest_category_id')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
