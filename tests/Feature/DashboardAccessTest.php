<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expression;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $researcher;
    private User $regularUser;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions if any
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Create researcher user
        $this->researcher = User::factory()->create();
        $this->researcher->assignRole('researcher');

        // Create regular user
        $this->regularUser = User::factory()->create();

        // Create category
        $this->category = Category::create([
            'name' => 'Kecemasan',
            'slug' => 'kecemasan',
            'is_active' => true,
        ]);
    }

    /**
     * Test admin can access admin dashboard and see correct stats.
     */
    public function test_admin_can_access_admin_dashboard()
    {
        Expression::create([
            'category_id' => $this->category->id,
            'content' => 'Aku merasa cemas.',
            'display_name' => 'Anonim',
            'status' => 'pending',
            'is_anonymous' => true,
            'consent_agreed_at' => now(),
        ]);

        Expression::create([
            'category_id' => $this->category->id,
            'content' => 'Aku merasa sangat senang.',
            'display_name' => 'Budi',
            'status' => 'approved',
            'is_anonymous' => false,
            'consent_agreed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('stats');
        $response->assertViewHas('recentExpressions');
        
        // Assert the stats counts match
        $stats = $response->viewData('stats');
        $this->assertEquals(2, $stats['total']);
        $this->assertEquals(1, $stats['pending']);
        $this->assertEquals(1, $stats['approved']);
    }

    /**
     * Test researcher can access researcher dashboard and see approved expressions.
     */
    public function test_researcher_can_access_researcher_dashboard()
    {
        Expression::create([
            'category_id' => $this->category->id,
            'content' => 'Aku merasa cemas.',
            'display_name' => 'Anonim',
            'status' => 'pending',
            'is_anonymous' => true,
            'consent_agreed_at' => now(),
        ]);

        Expression::create([
            'category_id' => $this->category->id,
            'content' => 'Aku merasa sangat senang.',
            'display_name' => 'Budi',
            'status' => 'approved',
            'is_anonymous' => false,
            'consent_agreed_at' => now(),
        ]);

        $response = $this->actingAs($this->researcher)->get(route('researcher.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('researcher.dashboard');
        $response->assertViewHas('expressions');
        
        // Check only approved expressions are returned
        $expressions = $response->viewData('expressions');
        $this->assertCount(1, $expressions);
        $this->assertEquals('Budi', $expressions->first()->display_name);
    }

    /**
     * Test unauthorized users cannot access admin or researcher dashboards.
     */
    public function test_regular_user_cannot_access_dashboards()
    {
        // Try accessing admin dashboard
        $responseAdmin = $this->actingAs($this->regularUser)->get(route('admin.dashboard'));
        $responseAdmin->assertStatus(403);

        // Try accessing researcher dashboard
        $responseResearcher = $this->actingAs($this->regularUser)->get(route('researcher.dashboard'));
        $responseResearcher->assertStatus(403);
    }

    /**
     * Test guest redirects to login.
     */
    public function test_guest_is_redirected_to_login()
    {
        $responseAdmin = $this->get(route('admin.dashboard'));
        $responseAdmin->assertRedirect('/login');

        $responseResearcher = $this->get(route('researcher.dashboard'));
        $responseResearcher->assertRedirect('/login');
    }
}
