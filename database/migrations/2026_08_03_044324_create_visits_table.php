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
    {Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visit_code', 30)->unique();
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('purpose_id')->constrained('visit_purposes')->onDelete('cascade');
            $table->foreignId('source_id')->nullable()->constrained('lead_sources')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('check_in_at');
            $table->dateTime('meeting_start_at')->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->string('status', 30)->default('waiting');
            $table->integer('queue_number')->nullable();
            $table->text('meeting_result')->nullable();
            $table->string('potential_level', 20)->nullable();
            $table->text('next_action')->nullable();
            $table->dateTime('follow_up_at')->nullable();
            $table->boolean('is_converted_to_lead')->default(false);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
