<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_redirects_authenticated_user_to_dashboard_view(): void
    {
        User::factory()->create([
            'name' => 'Kasir Admin',
            'email' => 'admin@kasir.test',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@kasir.test',
            'password' => 'password123',
        ]);

        $response->assertRedirectContains('/dashboard');
        $this->followRedirects($response)->assertSee('KASIR UMKM');
    }

    public function test_root_route_redirects_guest_to_login_and_authenticated_user_to_dashboard(): void
    {
        $guestResponse = $this->get('/');
        $guestResponse->assertRedirect(route('login'));

        $user = User::factory()->create();

        $authResponse = $this->actingAs($user)->get('/');
        $authResponse->assertRedirect(route('dashboard'));
    }
}
