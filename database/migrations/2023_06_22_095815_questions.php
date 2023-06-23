<?php

use App\Models\Test;
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
            $table->foreignIdFor(Test::class);
            $table->timestamps();
            $table->string('type');
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
           $table->string('question');
           $table->string('path')->nullable();

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
