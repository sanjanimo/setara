<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('modules', function (Blueprint $table) {
        $table->id();

        $table->string('title');
        $table->string('slug')->unique();
        $table->string('illustration')->nullable();

        $table->string('target')->default('umum'); // umum, anak, lansia
        $table->string('level')->default('basic'); // TAMBAHKAN BARIS INI
        $table->text('description')->nullable();

        $table->boolean('is_published')->default(true);
        $table->unsignedInteger('sort_order')->default(0);

        $table->timestamps();

        $table->index('target');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
