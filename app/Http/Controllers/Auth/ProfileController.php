<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfilePhotoRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show(): View
    {
        return view('auth.profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Show the edit profile form.
     */
    public function edit(): View
    {
        return view('auth.profile-edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        $user->update($request->validated());

        return back()->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Update the user's email.
     */
    public function updateEmail(UpdateEmailRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        $user->update(['email' => $request->email]);

        return back()->with('success', 'Email berhasil diperbarui!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(UpdateProfilePhotoRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->update(['profile_photo' => $path]);

        return back()->with('success', 'Foto profile berhasil diperbarui!');
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto(): RedirectResponse
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->update(['profile_photo' => null]);
        }

        return back()->with('success', 'Foto profile berhasil dihapus!');
    }
}
