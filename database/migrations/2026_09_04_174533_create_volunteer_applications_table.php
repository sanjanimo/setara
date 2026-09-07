<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('panti_id')
                ->constrained('pantis')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('youth_profile_id')
                ->nullable()
                ->constrained('youth_profiles')
                ->nullOnDelete();

            $table->string('activity_type')->default('kunjungan'); // kunjungan, mentor, bantuan_logistik

            $table->text('motivation');
            $table->text('skills')->nullable();
            $table->string('organization')->nullable();
            $table->string('availability')->nullable();

            $table->string('status')->default('diajukan'); // diajukan, disetujui, ditolak, selesai

            $table->date('scheduled_date')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['panti_id', 'status']);
            $table->index('activity_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
