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
        $tables = ['administrateurs', 'beneficiaires_effectifs', 'personnes_habilites', 'actionnariat'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->integer('percentage')->nullable()->after('note');
                $table->string('table_match', 255)->nullable()->after('percentage');
                $table->string('match_id', 500)->nullable()->after('table_match');
            });
        }
    }

    public function down(): void
    {
        $tables = ['administrateurs', 'beneficiaires_effectifs', 'personnes_habilites', 'actionnariat'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['percentage', 'table_match', 'match_id']);
            });
        }
    }
};
