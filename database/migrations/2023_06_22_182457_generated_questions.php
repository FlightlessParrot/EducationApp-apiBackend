<?php

use App\Models\GeneratedTest;
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
        Schema::create('generated_questions', function(Blueprint $table){
            $table->foreignIdFor(GeneratedTest::class);
            $table->foreignIdFor(Question::class);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
