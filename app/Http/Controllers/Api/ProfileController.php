<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
        ]);

        $user->forceFill($validated)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:1024', // 1MB Max
        ]);

        $user = $request->user();

        if (isset($request->photo)) {
            // $user->updateProfilePhoto($request->file('photo')); 
            // Custom DB Storage logic
            $file = $request->file('photo');
            $user->forceFill([
                'profile_photo_path' => 'profile-photos/' . $file->hashName(), // Keep path just in case
                'profile_photo_data' => file_get_contents($file->getRealPath()),
            ])->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile photo uploaded successfully',
            // Return our custom serving route
            'photo_url' => route('images.users', $user->id),
        ]);
    }
}
