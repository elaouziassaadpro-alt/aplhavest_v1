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
        Schema::create('opc_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etablissement_id')
                  ->constrained('etablissements')
                  ->onDelete('cascade');

            $table->string('opc')->nullable();
            $table->string('incrument')->nullable();
            $table->string('ni')->nullable();
            $table->string('fs')->nullable();
            $table->string('rg')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opc_files');
    }
};
