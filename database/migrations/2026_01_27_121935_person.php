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
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->string('person_name', 30);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->unsignedInteger('reading_goal')->default(0);
            $table->unsignedInteger('books_read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person');
    }
};
