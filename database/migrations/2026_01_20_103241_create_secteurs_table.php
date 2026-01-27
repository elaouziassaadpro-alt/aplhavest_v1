<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secteurs', function (Blueprint $table) {
            $table->id(); // primary key, BIGINT UNSIGNED
            $table->string('libelle', 150)->nullable();
            $table->string('niveauRisque', 10)->default('NR')->nullable();
            $table->integer('noteRisque')->default(0);
            $table->string('source', 300)->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secteurs');
    }
};

