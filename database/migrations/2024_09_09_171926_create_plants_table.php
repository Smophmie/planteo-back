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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('type')->nullable();
            $table->string('description');
            $table->string('sowing_period')->nullable();
            $table->string('planting_period')->nullable();
            $table->string('harvest_period');
            $table->string('soil');
            $table->string('watering');
            $table->string('exposure');
            $table->string('maintenance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
