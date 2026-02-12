<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('info_general_id'); // FK to infos_generales
            
            $table->string('nom', 100)->nullable();
            $table->string('prenom', 100)->nullable();
            $table->string('fonction', 100)->nullable();
            $table->string('telephone', 50)->nullable();
            $table->string('email', 150)->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('info_general_id')
                  ->references('id')->on('info_generales')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
