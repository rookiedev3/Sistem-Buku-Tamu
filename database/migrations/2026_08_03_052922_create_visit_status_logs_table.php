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
        Schema::create('visit_status_logs', function (Blueprint $table) {
            $table->bigInteger('visit_id');
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30);
            $table->bigInteger('changed_by')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_status_logs');
    }
};
