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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_code', 30)->unique();
            $table->string('name')->default('Review Device');
            $table->enum('type', ['qr_nfc', 'qr_only', 'nfc_only'])->default('qr_nfc');
            $table->foreignId('business_id')->nullable()->constrained('businesses')->nullOnDelete();
            $table->enum('status', ['unactivated', 'active', 'inactive', 'blocked'])->default('unactivated')->index();
            $table->text('google_review_url_override')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('total_scans')->default(0);
            $table->unsignedBigInteger('total_qr_scans')->default(0);
            $table->unsignedBigInteger('total_nfc_scans')->default(0);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'business_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
