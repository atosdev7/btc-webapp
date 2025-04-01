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
        Schema::create('tank_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->smallInteger('tank_id');

            $table->float('current_temp')->default(0);
            $table->boolean('solenoid')->default(false);
            $table->integer('max_rtd')->default(0);
            $table->integer('max_status')->default(false);
            $table->integer('sol_time')->default(0);

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->unique(['device_id', 'tank_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_states');
    }
};
