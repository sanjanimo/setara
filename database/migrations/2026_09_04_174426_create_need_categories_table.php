<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('need_categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->string('target')->default('umum'); // anak, lansia, umum
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('target');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('need_categories');
    }
};
