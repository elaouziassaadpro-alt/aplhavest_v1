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
        $tables = [
            'info_generales',
            'actionnariat',
            'administrateurs',
            'beneficiaires_effectifs',
            'personnes_habilites',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'match_name')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->renameColumn('match_name', 'match_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'info_generales',
            'actionnariat',
            'administrateurs',
            'beneficiaires_effectifs',
            'personnes_habilites',
        ];

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'match_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->renameColumn('match_id', 'match_name');
                });
            }
        }
    }
};
