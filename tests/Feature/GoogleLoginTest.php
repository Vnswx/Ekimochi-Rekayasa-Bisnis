<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Google OAuth redirect endpoint.
     */
    public function test_google_redirect_endpoint_works(): void
    {
        $response = $this->get('/auth/google');

        // Should redirect to Google OAuth (302)
        $response->assertStatus(302);
        
        // Should redirect to Google OAuth URL
        $this->assertStringContainsString(
            'accounts.google.com/o/oauth2/auth',
            $response->headers->get('Location')
        );
    }

    /**
     * Test User model supports Google fields in fillable.
     */
    public function test_user_model_has_google_fields_in_fillable(): void
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertContains('google_id', $fillable);
        $this->assertContains('google_token', $fillable);
        $this->assertContains('google_refresh_token', $fillable);
    }

    /**
     * Test that Google config is properly set.
     */
    public function test_google_oauth_config_is_set(): void
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        $this->assertNotEmpty($clientId, 'Google Client ID should be configured');
        $this->assertNotEmpty($clientSecret, 'Google Client Secret should be configured');
        $this->assertNotEmpty($redirectUri, 'Google Redirect URI should be configured');
        
        $this->assertStringContainsString('/auth/google/callback', $redirectUri);
    }
}
