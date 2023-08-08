<?php

use App\Models\Team;
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
        Schema::create('team_test', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Team::class);
            $table->foreignIdFor(Test::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_test');
    }
};
