<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth.
     * Note: Requires Laravel Socialite to be installed and configured.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        try {
            // Fix SSL certificate path for Guzzle/Socialite
            $certPath = 'C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\extras\ssl\cacert.pem';
            
            return Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client([
                    'verify' => file_exists($certPath) ? $certPath : true
                ]))
                ->redirect();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Google belum dikonfigurasi. Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            // Fix SSL certificate path for Guzzle/Socialite
            $certPath = 'C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\extras\ssl\cacert.pem';
            
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client([
                    'verify' => file_exists($certPath) ? $certPath : true
                ]))
                ->user();

            // Find existing user by google_id or email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update existing user with Google info
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'username' => $this->generateUniqueUsername($googleUser->getEmail()),
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'password' => Hash::make(Str::random(24)), // Random password for Google users
                    'role' => 'user', // Default role - TIDAK MENGUBAH ROLE ADMIN
                    'email_verified_at' => now(), // Auto-verify Google users
                ]);
            }

            // Login user
            Auth::login($user, true);

            // Redirect based on role (MULTI-ROLE SUPPORT)
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Admin!');
            }

            return redirect()->route('dashboard')->with('success', 'Login dengan Google berhasil!');

        } catch (Exception $e) {
            // Log error untuk debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal login dengan Google: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate unique username from email.
     */
    private function generateUniqueUsername(string $email): string
    {
        $username = Str::before($email, '@');
        $username = Str::slug($username, '');
        
        // Check if username exists
        $originalUsername = $username;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
