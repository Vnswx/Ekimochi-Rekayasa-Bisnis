<?php

namespace Tests\Feature;

use App\Models\Promo;
use App\Models\User;
use App\Notifications\PromoPublishedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // APP_URL proyek ini memakai subfolder (…/public), sedangkan request
        // pengujian dibuat dari root, jadi root URL dipaksa ke host saja.
        URL::forceRootUrl('http://localhost');
    }

    private function makeUser(array $attributes = []): User
    {
        static $i = 0;
        $i++;

        return User::create(array_merge([
            'name' => 'Budi ' . $i,
            'username' => 'budi' . $i . uniqid(),
            'email' => 'budi' . $i . uniqid() . '@example.com',
            'password' => 'password',
            'role' => 'user',
        ], $attributes));
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/notification')->assertRedirect('/login');
    }

    public function test_notification_page_renders_preferences_and_navbar_entry(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get('/notification');

        $response->assertOk();
        $response->assertSee('Preferensi Notifikasi');
        $response->assertSee('Kategori Notifikasi');
        $response->assertSee('Cara Menerima Notifikasi');
        $response->assertSee('name="notify_promo"', false);
        $response->assertSee('name="notify_push"', false);
        $response->assertSee(route('notification.index'), false);
        $response->assertSee('Belum ada notifikasi');

        $settings = substr_count($response->getContent(), 'name="notify_');
        $this->assertSame(7, $settings, 'Halaman harus punya 7 toggle preferensi.');
        $this->assertSame(1, substr_count($response->getContent(), '<main>'));
    }

    public function test_user_can_update_notification_preferences(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->put('/notification', [
            'notify_promo' => 1,
            'notify_order' => 0,
            'notify_product' => 1,
            'notify_outlet' => 1,
            'notify_email' => 0,
            'notify_whatsapp' => 1,
            'notify_push' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue($user->notificationPreference('promo'));
        $this->assertFalse($user->notificationPreference('order'));
        $this->assertTrue($user->notificationPreference('outlet'));
        $this->assertFalse($user->notificationPreference('email'));
        $this->assertTrue($user->notificationPreference('whatsapp'));

        // Field yang tidak dikirim dianggap nonaktif.
        $this->actingAs($user)->put('/notification', [])->assertRedirect();
        $this->assertFalse($user->fresh()->notificationPreference('promo'));
    }

    public function test_active_promo_notifies_users_who_want_promo_info(): void
    {
        $penerima = $this->makeUser();
        $penolak = $this->makeUser(['notify_promo' => false]);
        $admin = $this->makeUser(['role' => 'admin', 'username' => 'admin' . uniqid()]);

        $response = $this->actingAs($admin)->post('/admin/promo', [
            'name' => 'Promo Gajian Mochi',
            'description' => 'Diskon spesial akhir bulan.',
            'discount_type' => 'percentage',
            'discount_value' => 15,
            'min_purchase' => 0,
            'starts_at' => now()->subDay()->toDateTimeString(),
            'ends_at' => now()->addDays(7)->toDateTimeString(),
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.promo.index'));
        $response->assertSessionHas('success');

        $promo = Promo::where('name', 'Promo Gajian Mochi')->firstOrFail();

        $this->assertSame(1, $penerima->notifications()->count());
        $this->assertSame(0, $penolak->notifications()->count());

        $notification = $penerima->notifications()->first();
        $this->assertSame(PromoPublishedNotification::class, $notification->type);
        $this->assertStringContainsString('Promo Gajian Mochi', $notification->data['title']);
        $this->assertSame($promo->id, $notification->data['promo_id']);
        $this->assertNull($notification->read_at);

        $page = $this->actingAs($penerima)->get('/notification');
        $page->assertSee('Promo baru: Promo Gajian Mochi');
        $page->assertSee('Tandai dibaca');
    }

    public function test_inactive_promo_does_not_notify(): void
    {
        $user = $this->makeUser();
        $admin = $this->makeUser(['role' => 'admin', 'username' => 'admin' . uniqid()]);

        $this->actingAs($admin)->post('/admin/promo', [
            'name' => 'Draft Promo',
            'discount_type' => 'nominal',
            'discount_value' => 5000,
        ])->assertRedirect();

        $this->assertSame(0, $user->notifications()->count());
    }

    public function test_user_can_mark_notifications_as_read(): void
    {
        $user = $this->makeUser();
        $promo = Promo::create([
            'name' => 'Promo Uji Baca',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'is_active' => true,
        ]);

        $user->notify(new PromoPublishedNotification($promo));
        $this->assertSame(1, $user->unreadNotifications()->count());

        $notification = $user->notifications()->first();
        $this->actingAs($user)->post(route('notification.read', $notification))->assertRedirect();
        $this->assertSame(0, $user->unreadNotifications()->count());

        $user->notify(new PromoPublishedNotification($promo));
        $this->actingAs($user)->post(route('notification.readAll'))->assertRedirect();
        $this->assertSame(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_user_cannot_mark_someone_elses_notification_as_read(): void
    {
        $owner = $this->makeUser();
        $other = $this->makeUser();
        $promo = Promo::create([
            'name' => 'Promo Uji Akses',
            'discount_type' => 'percentage',
            'discount_value' => 5,
            'is_active' => true,
        ]);

        $owner->notify(new PromoPublishedNotification($promo));
        $notification = $owner->notifications()->first();

        $this->actingAs($other)
            ->post(route('notification.read', $notification))
            ->assertForbidden();
    }
}
