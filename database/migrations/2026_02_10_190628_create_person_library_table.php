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
        Schema::create('person_library', function (Blueprint $table) {
                $table->foreignId('person_id')->constrained('person')->cascadeOnDelete();
                $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['person_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_library');
    }
};
