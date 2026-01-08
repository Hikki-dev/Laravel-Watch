<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    public function showProductImage($id)
    {
        $image = ProductImage::find($id);

        if (!$image || !$image->image_data) {
            abort(404);
        }

        return Response::make($image->image_data, 200, [
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

        return Response::make($user->profile_photo_data, 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
