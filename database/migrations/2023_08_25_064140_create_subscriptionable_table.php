<?php

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
        Schema::create('subscriptionables', function (Blueprint $table) {
          
            $table->timestamps();
            $table->foreignIdFor(Subscription::class);
            $table->integer('subscriptionables_id');
            $table->string('subscriptionables_type');
            $table->date('expiration_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptionables');
    }
};
