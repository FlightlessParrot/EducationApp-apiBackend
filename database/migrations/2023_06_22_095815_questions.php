<?php

use App\Models\Category;
use App\Models\Test;
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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('type',['open','one-answer','many-answers','short-answer','pairs','order']);
 
           $table->text('question');
           $table->text('explanation')->nullable();
           $table->string('path')->nullable();
           $table->boolean('custom')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
