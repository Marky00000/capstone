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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',12);
            $table->string('email',40)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('password',100);
            $table->enum('usertype', ['user','admin'])->default('user');
            $table->rememberToken(20);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email',20)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'markejano0@gmail.com',
            'password' => Hash::make('arfiladmin'),
            'usertype' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        $table->dropColumn('otp_expires_at');


    }
};
