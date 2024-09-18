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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the booking
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assigned_admin')->nullable();
            $table->string('name');
            $table->string('contact');
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->date('site_visit_date');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_admin')->references('id')->on('users')->onDelete('set null'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
