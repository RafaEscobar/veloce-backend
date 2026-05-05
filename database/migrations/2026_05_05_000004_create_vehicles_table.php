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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('vehicle_type_id')->constrained();
            $table->foreignId('vehicle_status_id')->constrained();
            $table->string('plates');
            $table->string('serial_number');
            $table->string('gasoline_type');
            $table->string('oil_type');
            $table->string('model_name');
            $table->string('photo')->nullable();
            $table->year('model_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
