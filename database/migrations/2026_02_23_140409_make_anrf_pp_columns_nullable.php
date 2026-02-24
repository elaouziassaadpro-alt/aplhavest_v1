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
        Schema::table('a_n_r_f__p_p_s', function (Blueprint $table) {
            $table->string('nom')->nullable()->change();
            $table->string('prenom')->nullable()->change();
            $table->string('date_naissance')->nullable()->change();
            $table->string('profession')->nullable()->change();
            $table->string('nationalite')->nullable()->change();
            $table->string('identifiant')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('a_n_r_f__p_p_s', function (Blueprint $table) {
            $table->string('nom')->nullable(false)->change();
            $table->string('prenom')->nullable(false)->change();
            $table->string('date_naissance')->nullable(false)->change();
            $table->string('profession')->nullable(false)->change();
            $table->string('nationalite')->nullable(false)->change();
            $table->string('identifiant')->nullable(false)->change();
        });
    }
};
