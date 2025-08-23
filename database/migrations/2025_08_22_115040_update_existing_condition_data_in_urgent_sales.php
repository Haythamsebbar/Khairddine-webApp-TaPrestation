<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les données existantes pour correspondre aux nouvelles valeurs
        DB::table('urgent_sales')
            ->where('condition', 'new')
            ->update(['condition' => 'excellent']);
            
        DB::table('urgent_sales')
            ->where('condition', 'used')
            ->update(['condition' => 'good']);
            
        // 'good' et 'fair' restent inchangés car ils existent dans les deux énumérations
        
        // Maintenant modifier l'énumération
        DB::statement("ALTER TABLE urgent_sales MODIFY COLUMN `condition` ENUM('excellent', 'very_good', 'good', 'fair', 'poor') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux anciennes valeurs
        DB::table('urgent_sales')
            ->where('condition', 'excellent')
            ->update(['condition' => 'new']);
            
        DB::table('urgent_sales')
            ->where('condition', 'very_good')
            ->update(['condition' => 'good']);
            
        DB::table('urgent_sales')
            ->where('condition', 'poor')
            ->update(['condition' => 'fair']);
            
        // Revenir à l'ancienne énumération
        DB::statement("ALTER TABLE urgent_sales MODIFY COLUMN `condition` ENUM('new', 'good', 'used', 'fair') NOT NULL");
    }
};
