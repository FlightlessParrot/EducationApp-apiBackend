<?php

use App\Models\Category;
use App\Models\Undercategory;
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
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('question');
            $table->string('path')->nullable();
            $table->text('answer');
            $table->integer('flashcardable_id');
            $table->string('flashcardable_type');
            $table->foreignIdFor(Category::class)->nullable();
            $table->foreignIdFor(Undercategory::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flashcards');
    }
};
