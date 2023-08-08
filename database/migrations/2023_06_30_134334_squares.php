<?php

use App\Models\Question;
use App\Models\Square;
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
        Schema::create('squares', function (Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Question::class);
            $table->string('text');
            $table->foreignId('brother')->nullable();
            $table->integer('order',false, true)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('squares');
    }
};
