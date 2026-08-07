<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Aman didrop karena tabel leads masih kosong (schema lama tidak dipakai lagi)
        Schema::dropIfExists('leads');

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests');
            $table->foreignId('visit_id')->nullable()->constrained('visits')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['new', 'contacted', 'negotiation', 'deal', 'lost'])->default('new');
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->dateTime('follow_up_at')->nullable();
            $table->timestamps();
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->foreignId('lead_id')->nullable()->after('visit_id')->constrained('leads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('lead_id');
        });

        Schema::dropIfExists('leads');
    }
};