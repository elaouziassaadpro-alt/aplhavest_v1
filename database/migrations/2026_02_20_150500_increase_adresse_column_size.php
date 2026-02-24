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
        Schema::table('c_n_a_s_n_u_s', function (Blueprint $table) {
            $table->string('adresse', 1000)->nullable()->change();
            $table->text('comment1')->nullable()->change();
            $table->string('dateAjout', 500)->nullable()->change();
        });

        Schema::table('a_n_r_f_s', function (Blueprint $table) {
            $table->string('dateAjout', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('c_n_a_s_n_u_s', function (Blueprint $table) {
            $table->string('adresse', 500)->nullable()->change();
            $table->string('comment1', 5000)->nullable()->change();
            $table->dateTime('dateAjout')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });

        Schema::table('a_n_r_f_s', function (Blueprint $table) {
            $table->dateTime('dateAjout')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });
    }
};
