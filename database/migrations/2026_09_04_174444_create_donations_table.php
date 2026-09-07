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

            $table->foreignId('panti_id')
                ->constrained('pantis')
                ->cascadeOnDelete();

            $table->foreignId('panti_need_id')
                ->nullable()
                ->constrained('panti_needs')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('donor_name');
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();

            $table->string('type')->default('barang'); // barang, tenaga
            $table->unsignedInteger('quantity')->nullable();

            $table->text('message')->nullable();

            $table->string('status')->default('diajukan'); // diajukan, dikonfirmasi, ditolak, selesai

            $table->string('proof_url')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['panti_id', 'status']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
