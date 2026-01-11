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
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('product_images', function (Blueprint $table) {
                $table->binary('image_data')->nullable();
            });
            Schema::table('users', function (Blueprint $table) {
                $table->binary('profile_photo_data')->nullable();
            });
        } else {
            // Execute raw SQL to add LONGBLOB column for MySQL/MariaDB
            DB::statement("ALTER TABLE product_images ADD COLUMN image_data LONGBLOB NULL AFTER image_path");
            DB::statement("ALTER TABLE users ADD COLUMN profile_photo_data LONGBLOB NULL AFTER profile_photo_path");
        }
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
