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
        Schema::create('tank_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->smallInteger('tank_id');

            $table->tinyInteger('control_mode')->default(0);
            $table->float('target_temp')->default(0);
            $table->float('pt100_cal')->default(0);
            $table->float('degree_per_day')->default(0);
            $table->boolean('update_flag')->default(false);

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');

            $table->unique(['device_id', 'tank_id']);            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_configs');
    }
};
