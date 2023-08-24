<?php

use App\Models\GeneratedQuestion;
use App\Models\GeneratedTest;
use App\Models\User;
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
        Schema::create('open_answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('answer');
            $table->foreignIdFor(GeneratedQuestion::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_answers');
    }
};
