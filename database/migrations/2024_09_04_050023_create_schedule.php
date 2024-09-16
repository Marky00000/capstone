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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the schedule
            $table->unsignedBigInteger('project_id');

            // Phase one details
            $table->string('phase_one')->nullable();
            $table->enum('phase_one_progress', ['10', '20', '30', '40', '50', '60', '70', '80', '90', '100'])->nullable();
            
            // Phase two details
            $table->string('phase_two')->nullable();
            $table->enum('phase_two_progress', ['10', '20', '30', '40', '50', '60', '70', '80', '90', '100'])->nullable();
            
            // Phase three details
            $table->string('phase_three')->nullable();
            $table->enum('phase_three_progress', ['10', '20', '30', '40', '50', '60', '70', '80', '90', '100'])->nullable();
            
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('working_days')->nullable();

            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
