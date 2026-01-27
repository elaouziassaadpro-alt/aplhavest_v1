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
        Schema::create('formes_juridiques', function (Blueprint $table) {
            $table->id(); // creates `id` as primary key, auto-increment
            $table->string('libelle', 50)->nullable();
            $table->string('code', 10)->nullable();
            $table->timestamps(); // optional, adds `created_at` and `updated_at`
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formes_juridiques');
    }
};
