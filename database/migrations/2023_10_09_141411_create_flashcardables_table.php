<?php

use App\Models\Flashcard;
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
        Schema::create('flashcardables', function (Blueprint $table) {
           $table->foreignIdFor(Flashcard::class);
           $table->integer('flashcardable_id');
           $table->string('flashcardable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcardables');
    }
};
