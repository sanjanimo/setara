<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('panti_id')
                ->constrained('pantis')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('volunteer_application_id')
                ->nullable()
                ->constrained('volunteer_applications')
                ->nullOnDelete();

            $table->date('activity_date');
            $table->text('summary');

            $table->boolean('follow_up_needed')->default(false);
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['panti_id', 'activity_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_reports');
    }
};
