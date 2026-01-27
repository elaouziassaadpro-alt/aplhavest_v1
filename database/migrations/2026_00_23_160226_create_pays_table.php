<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pays', function (Blueprint $table) {
            $table->id(); // primary key auto-increment
            $table->string('libelle', 255)->nullable();
            $table->string('iso', 10)->nullable();
            $table->string('niveauRisque', 50)->nullable(); // adjust length if needed
            $table->timestamps(); // optional: created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pays');
    }
};
