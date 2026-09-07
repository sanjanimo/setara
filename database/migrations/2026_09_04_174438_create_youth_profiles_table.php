<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youth_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('panti_id')
                ->constrained('pantis')
                ->cascadeOnDelete();

            $table->string('initials');
            $table->unsignedTinyInteger('age');

            $table->text('interests')->nullable();
            $table->text('skill_goals')->nullable();
            $table->text('training_needs')->nullable();

            $table->boolean('mentor_needed')->default(false);

            $table->string('status')->default('baru'); // baru, didampingi, selesai
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['panti_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_profiles');
    }
};
