<?php

use App\Models\Category;
use App\Models\Question;
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
        Schema::create('category_question', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Question::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_question');
    }
};
