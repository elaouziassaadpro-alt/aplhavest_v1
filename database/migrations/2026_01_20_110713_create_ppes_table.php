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
        Schema::create('ppes', function (Blueprint $table) {
            $table->id(); // id int(11) auto-increment
            $table->string('libelle', 150)->nullable(); // libelle varchar(150) not null
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppes');
    }
};
