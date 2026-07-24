<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Expression;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpressionModerationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $nonAdmin;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

        // Buat user admin
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Buat user non-admin
        $this->nonAdmin = User::factory()->create();

        // Buat kategori
        $this->category = Category::create([
            'name' => 'Kecemasan',
            'slug' => 'kecemasan',
            'is_active' => true,
        ]);
    }

    /**
     * Test access control for moderation.
     */
    public function test_non_admin_cannot_access_moderation()
    {
        $response = $this->get(route('admin.expressions.index'));
        $response->assertRedirect('/login');

        $response = $this->actingAs($this->nonAdmin)->get(route('admin.expressions.index'));
        $response->assertStatus(403);
    }

    /**
     * Test access to moderation index.
     */
    public function test_admin_can_access_moderation_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.expressions.index'));
        $response->assertStatus(200);
        $response->assertViewHas('expressions');
    }

    /**
     * Test approving an expression (individual).
     */
    public function test_admin_can_approve_expression()
    {
        $expression = Expression::create([
            'category_id' => $this->category->id,
            'content' => 'Aku merasa sangat cemas hari ini.',
            'display_name' => 'Anonim',
            'status' => 'pending',
            'is_anonymous' => true,
            'consent_agreed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.expressions.approve', $expression));

        $response->assertRedirect();
        
        $expression->refresh();
        $this->assertEquals('approved', $expression->status);
        $this->assertEquals($this->admin->id, $expression->moderated_by);
        $this->assertNotNull($expression->moderated_at);

        // Verifikasi audit log
        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $expression->id,
            'subject_type' => Expression::class,
            'causer_id' => $this->admin->id,
            'description' => 'Ekspresi disetujui',
        ]);
    }

    /**
     * Test flagging an expression requires note (catatan_moderasi) min 10 characters (BR-5.2 & AC-5.7).
     * Saat ini test ini akan gagal karena controller belum mengimplementasikan validasi
     * dan UI mengirimkan default note "Ditandai oleh admin" via hidden field.
     */
    public function test_flagging_expression_requires_valid_note()
    {
        $expression = Expression::create([
            'category_id' => $this->category->id,
            'content' => 'Aku merasa cemas.',
            'display_name' => 'Anonim',
            'status' => 'pending',
            'is_anonymous' => true,
            'consent_agreed_at' => now(),
        ]);

        // Scenario 1: Kirim tanpa note / kosong
        $response = $this->actingAs($this->admin)
            ->from(route('admin.expressions.show', $expression))
            ->post(route('admin.expressions.flag', $expression), [
                'note' => ''
            ]);

        // Harus gagal validasi dan redirect back dengan session error
        $response->assertRedirect(route('admin.expressions.show', $expression));
        $response->assertSessionHasErrors(['note']);
        
        // Pastikan status tidak berubah
        $expression->refresh();
        $this->assertEquals('pending', $expression->status);

        // Scenario 2: Kirim note kurang dari 10 karakter (misal: "coba")
        $response = $this->actingAs($this->admin)
            ->from(route('admin.expressions.show', $expression))
            ->post(route('admin.expressions.flag', $expression), [
                'note' => 'coba'
            ]);

        $response->assertRedirect(route('admin.expressions.show', $expression));
        $response->assertSessionHasErrors(['note']);

        // Scenario 3: Kirim note valid >= 10 karakter
        $responseSuccess = $this->actingAs($this->admin)
            ->post(route('admin.expressions.flag', $expression), [
                'note' => 'Alasan flag karena tidak sopan'
            ]);

        $expression->refresh();
        $this->assertEquals('flagged', $expression->status);
        $this->assertEquals('Alasan flag karena tidak sopan', $expression->moderation_note); // atau catatan_moderasi
        $this->assertEquals($this->admin->id, $expression->moderated_by);
    }

    /**
     * Test bulk approve (AC-5.5 & BR-5.3).
     * Saat ini test ini akan gagal karena route bulk action belum didefinisikan.
     */
    public function test_admin_can_bulk_approve_expressions()
    {
        // Buat 5 ekspresi pending
        $expressions = [];
        for ($i = 0; $i < 5; $i++) {
            $expressions[] = Expression::create([
                'category_id' => $this->category->id,
                'content' => "Ekspresi pending ke-$i",
                'display_name' => 'Anonim',
                'status' => 'pending',
                'is_anonymous' => true,
                'consent_agreed_at' => now(),
            ]);
        }

        $ids = collect($expressions)->pluck('id')->toArray();

        // Kita coba panggil route bulk approve
        // Karena route ini kemungkinan besar belum ada, test ini akan memicu RouteNotFoundException
        // atau 404.
        $this->assertTrue(Route::has('admin.expressions.bulk-approve'), 'Route admin.expressions.bulk-approve tidak terdefinisi!');

        $response = $this->actingAs($this->admin)->post(route('admin.expressions.bulk-approve'), [
            'ids' => $ids
        ]);

        $response->assertRedirect();
        
        // Verifikasi semua ekspresi berubah ke approved
        foreach ($expressions as $exp) {
            $exp->refresh();
            $this->assertEquals('approved', $exp->status);
        }
    }

    /**
     * Test cleanup of controllers (AC-5.9 & AC-5.11).
     * Saat ini test ini akan gagal karena HomePage.php dan PublicPageController.php masih ada.
     */
    public function test_redundant_controllers_are_deleted()
    {
        $homePageExists = file_exists(app_path('Http/Controllers/pages/HomePage.php'));
        $publicPageControllerExists = file_exists(app_path('Http/Controllers/pages/PublicPageController.php'));

        $this->assertFalse($homePageExists, 'HomePage.php masih ada di app/Http/Controllers/pages/!');
        $this->assertFalse($publicPageControllerExists, 'PublicPageController.php masih ada di app/Http/Controllers/pages/!');
    }
}
