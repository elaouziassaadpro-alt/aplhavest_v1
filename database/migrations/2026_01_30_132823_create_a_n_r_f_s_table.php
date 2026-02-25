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
        Schema::create('a_n_r_f_s', function (Blueprint $table) {
            $table->id(); // id, auto-increment primary key
            $table->string('nom', 500)->nullable();
            $table->string('identifiant', 500)->nullable();
            $table->string('pays', 500)->nullable();
            $table->string('dateAjout', 500)->nullable();
            $table->string('activite', 500)->nullable();
            $table->timestamps(); // optional: created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_n_r_f_s');
    }
};
