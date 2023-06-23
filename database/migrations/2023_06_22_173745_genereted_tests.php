<?php

use App\Models\Test;
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
        Schema::create('generated_tests', function(Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(User::class);
            $table->timestamp('end_time')->nullable();
            $table->boolean('egzam')->nullable();
            $table->time('time')->nullable();
            $table->integer('questions_number',false,true);
            $table->foreignIdFor(Test::class);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_tests');
    }
};
