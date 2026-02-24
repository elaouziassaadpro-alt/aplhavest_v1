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
        Schema::table('info_generales', function (Blueprint $table) {
            $table->decimal('note')->default(0)->after('etablissement_id');
            $table->integer('percentage')->default(0)->after('note');
            $table->string('table_match')->nullable()->after('percentage');
            $table->string('match_name')->nullable()->after('table_match');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_generales', function (Blueprint $table) {
            $table->dropColumn(['note', 'percentage', 'table_match', 'match_id']);
        });
    }
};
