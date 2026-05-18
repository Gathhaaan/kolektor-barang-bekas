<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // donor
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('condition', ['baru', 'sangat_baik', 'baik', 'cukup_baik']);
            $table->json('photos')->nullable();
            $table->string('pickup_address');
            $table->enum('status', [
                'pending',     // waiting admin verification
                'approved',    // visible in catalog
                'rejected',    // rejected by admin
                'assigned',    // courier assigned
                'picked_up',   // courier picked up
                'delivered',   // successfully delivered
                'completed',   // points awarded
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('admin_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
