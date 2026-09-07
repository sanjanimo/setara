<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pantis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // anak, jompo, campuran

            $table->text('description')->nullable();

            $table->string('province');
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('address')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();

            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('total_residents')->default(0);
            $table->unsignedInteger('children_count')->default(0);
            $table->unsignedInteger('elderly_count')->default(0);
            $table->unsignedInteger('staff_count')->default(0);

            $table->string('verification_status')->default('pending');
            $table->text('verification_note')->nullable();

            $table->string('logo_url')->nullable();
            $table->string('cover_url')->nullable();

            $table->boolean('consent_agreement')->default(false);
            $table->string('location_precision')->default('approximate');

            $table->unsignedTinyInteger('urgency_score')->default(0);
            $table->string('urgency_status')->default('aman');
            $table->timestamp('urgency_calculated_at')->nullable();

            $table->timestamps();

            $table->index('city');
            $table->index('type');
            $table->index('verification_status');
            $table->index('urgency_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pantis');
    }
};
