<?php

use App\Models\Subscription;
use App\Models\Team;
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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
           $table->string('name');
           $table->string('path')->nullable();
           $table->enum('role',['general','custom','egzam'])->default('general');
           $table->boolean('fillable')->default(true);
           $table->time('maximum_time')->nullable();
           $table->integer('gandalf',false,true)->default(50);
           $table->foreignIdFor(User::class)->nullable();
   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
