<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->enum('initial', ['paid', 'partial', 'overdue'])->nullable();
            $table->enum('midterm', ['paid', 'partial', 'overdue'])->nullable();
            $table->enum('final', ['paid', 'partial', 'overdue'])->nullable();
            $table->decimal('inital_payment')->nullable(); 
            $table->decimal('midterm_payment')->nullable(); 
            $table->decimal('final_payment')->nullable(); 
            $table->string('initial_image')->nullable();
            $table->string('midterm_image')->nullable();
            $table->string('final_image')->nullable(); 
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
