<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add image_data to product_images table
        Schema::table('product_images', function (Blueprint $table) {
            // We use DB::statement for LONGBLOB as Laravel's binary() is sometimes just BLOB (64KB)
            // LONGBLOB is needed for images up to 4GB (plenty!)
        });
        
        // Execute raw SQL to add LONGBLOB column as Laravel schema builder doesn't always support it directly/consistently across drivers
        DB::statement("ALTER TABLE product_images ADD COLUMN image_data LONGBLOB NULL AFTER image_path");

        // Add profile_photo_data to users table
        Schema::table('users', function (Blueprint $table) {
             // Placeholder for schema builder if needed, but using raw SQL below for consistency
        });
        
        DB::statement("ALTER TABLE users ADD COLUMN profile_photo_data LONGBLOB NULL AFTER profile_photo_path");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('image_data');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo_data');
        });
    }
};
