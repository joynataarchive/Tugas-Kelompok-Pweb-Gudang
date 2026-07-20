<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Spatie roles
        $adminRole = Role::create(['name' => 'Super Admin']);
        $staffRole = Role::create(['name' => 'Staff Gudang']);

        // Create sample users
        $this->admin = User::factory()->create([
            'name' => 'Joni Owner Gudang',
            'email' => 'admin@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'Super Admin',
        ]);
        $this->admin->assignRole($adminRole);

        $this->staff = User::factory()->create([
            'name' => 'Agus Gudang',
            'email' => 'staff@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'Staff Gudang',
        ]);
        $this->staff->assignRole($staffRole);
    }

    /**
     * Test login page renders successfully.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
    }

    /**
     * Test successful login redirects to products index.
     */
    public function test_successful_login_redirects_to_products_index(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@inventory.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Test invalid login credentials.
     */
    public function test_invalid_login_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@inventory.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test guest redirection when accessing protected routes.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user redirection when accessing guest routes.
     */
    public function test_authenticated_user_cannot_access_guest_routes(): void
    {
        $response = $this->actingAs($this->admin)->get('/login');

        $response->assertRedirect(route('products.index'));
    }

    /**
     * Test role-based access control (RBAC).
     */
    public function test_role_based_access_control(): void
    {
        // Define a test route requiring Super Admin role
        $this->app['router']->get('/test-admin-only', function () {
            return 'Admin Only Content';
        })->middleware(['web', 'auth', 'role:Super Admin']);

        // Super Admin should be allowed
        $responseAdmin = $this->actingAs($this->admin)->get('/test-admin-only');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Admin Only Content');

        // Staff Gudang should get 403 Forbidden
        $responseStaff = $this->actingAs($this->staff)->get('/test-admin-only');
        $responseStaff->assertStatus(403);
    }

    /**
     * Test logout redirects to login and clears auth status.
     */
    public function test_logout_redirects_to_login_and_clears_auth(): void
    {
        $response = $this->actingAs($this->admin)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
