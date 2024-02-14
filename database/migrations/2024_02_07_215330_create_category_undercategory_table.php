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
        Schema::create('category_undercategory', function (Blueprint $table) {
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Undercategory::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_undercategory');
    }
};
