<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('module_quizzes', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('correct_option');
        });
    }

    public function down(): void
    {
        Schema::table('module_quizzes', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
