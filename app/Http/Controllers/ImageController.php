<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    // PostgreSQL returns bytea columns as a stream; MySQL and SQLite return a string.
    private static function bytes($data): string
    {
        return is_resource($data) ? stream_get_contents($data) : $data;
    }

    public function showProductImage($id)
    {
        $image = ProductImage::find($id);

        if (!$image || !$image->image_data) {
            abort(404);
        }

        return Response::make(self::bytes($image->image_data), 200, [
            'Content-Type' => 'image/jpeg', // Defaulting to jpeg, browser usually handles it fine
            'Cache-Control' => 'public, max-age=86400', // Cache for 1 day
        ]);
    }

    public function showUserImage($id)
    {
        $user = User::find($id);

        if (!$user || !$user->profile_photo_data) {
             // Return default placeholder or 404
             abort(404);
        }

        return Response::make(self::bytes($user->profile_photo_data), 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
