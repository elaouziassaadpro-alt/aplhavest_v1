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
            $table->dropColumn(['dateAjout', 'aliasName']);
        });
        Schema::table('c_n_a_s_n_u_s', function (Blueprint $table) {
            $table->string('dateAjout', 500)->nullable();
            $table->text('aliasName')->nullable();
        });

        Schema::table('a_n_r_f_s', function (Blueprint $table) {
            $table->dropColumn('dateAjout');
        });
        Schema::table('a_n_r_f_s', function (Blueprint $table) {
            $table->string('dateAjout', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('c_n_a_s_n_u_s', function (Blueprint $table) {
            $table->dropColumn(['dateAjout', 'aliasName']);
        });
        Schema::table('c_n_a_s_n_u_s', function (Blueprint $table) {
            $table->dateTime('dateAjout')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('aliasName', 500)->nullable();
        });

        Schema::table('a_n_r_f_s', function (Blueprint $table) {
            $table->dropColumn('dateAjout');
        });
        Schema::table('a_n_r_f_s', function (Blueprint $table) {
            $table->dateTime('dateAjout')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }
};
