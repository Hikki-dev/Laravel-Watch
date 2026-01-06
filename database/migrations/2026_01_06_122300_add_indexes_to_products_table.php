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
        Schema::table('products', function (Blueprint $table) {
            // Indexes for frequently searched/filtered columns
            $table->index('name');
            $table->index('brand');
            $table->index('price');
            $table->index('is_featured');
            $table->index('is_active');
            
            // Compound index for category filtering (often filtered by category + active)
            $table->index(['category_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['brand']);
            $table->dropIndex(['price']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['category_id', 'is_active']);
        });
    }
};
