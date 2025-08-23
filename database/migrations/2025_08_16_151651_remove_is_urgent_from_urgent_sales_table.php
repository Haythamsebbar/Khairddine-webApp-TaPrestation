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
        Schema::table('urgent_sales', function (Blueprint $table) {
            $table->dropColumn('is_urgent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('urgent_sales', function (Blueprint $table) {
            $table->boolean('is_urgent')->default(false)->after('longitude');
        });
    }
};
