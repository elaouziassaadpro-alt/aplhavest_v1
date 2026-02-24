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
    Schema::table('etablissements', function (Blueprint $table) {

        $table->boolean('validation')->nullable();
        $table->boolean('validation_AK')->nullable();

    });

    Schema::table('info_generales', function (Blueprint $table) {

        $table->boolean('validation')->nullable();
        $table->boolean('validation_AK')->nullable();

    });
}
};