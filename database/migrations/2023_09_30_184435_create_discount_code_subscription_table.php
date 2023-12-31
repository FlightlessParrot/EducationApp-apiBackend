<?php

use App\Models\DiscountCode;
use App\Models\Subscription;
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
        Schema::create('discount_code_subscription', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(DiscountCode::class);
            $table->foreignIdFor(Subscription::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_code_subscription');
    }
};
