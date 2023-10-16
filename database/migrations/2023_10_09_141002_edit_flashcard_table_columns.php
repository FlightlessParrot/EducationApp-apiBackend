<?php

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
        Schema::table('flashcards',function (Blueprint $table)
        {
                $table->dropColumn('flashcardable_id');
                $table->dropColumn('flashcardable_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcards', function (Blueprint $table) {

            $table->integer('flashcardable_id');
            $table->string('flashcardable_type');
        });
    }
};
