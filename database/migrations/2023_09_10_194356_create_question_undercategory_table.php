<?php

use App\Models\Question;
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
        Schema::create('question_undercategory', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Question::class);
            $table->foreignIdFor(Undercategory::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_undercategory');
    }
};
