<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panti_needs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('panti_id')
                ->constrained('pantis')
                ->cascadeOnDelete();

            $table->foreignId('need_category_id')
                ->constrained('need_categories')
                ->restrictOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('unit')->default('unit');
            $table->unsignedInteger('quantity_needed')->default(0);
            $table->unsignedInteger('current_stock')->default(0);
            $table->unsignedInteger('stock_days_remaining')->default(0);

            $table->string('priority')->default('sedang'); // rendah, sedang, tinggi, kritis
            $table->string('status')->default('aktif'); // aktif, terpenuhi, ditunda

            $table->timestamp('fulfilled_at')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['panti_id', 'status']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panti_needs');
    }
};
