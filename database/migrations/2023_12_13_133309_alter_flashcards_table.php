<?php

use App\Models\Category;
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
        Schema::table('flashcards',function (Blueprint $table)
        {
                $table->dropColumn('category_id');
                $table->dropColumn('undercategory_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashcards',function (Blueprint $table)
        {
        $table->foreignIdFor(Category::class)->nullable();
        $table->foreignIdFor(Undercategory::class)->nullable();
        });
    }
};
