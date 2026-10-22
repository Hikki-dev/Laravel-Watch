<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ordered frame URLs for the 360 viewer (one full turn of the watch).
     * Null or fewer than 8 frames means the product page shows the photo gallery only.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('spin_frames')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('spin_frames');
        });
    }
};
