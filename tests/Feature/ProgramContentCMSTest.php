<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ProgramContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProgramContentCMSTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $nonAdmin;

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
    }

    public function test_non_admin_cannot_access_cms()
    {
        // Guess / Tamu
        $response = $this->get(route('admin.program-contents.index'));
        $response->assertRedirect('/login');

        // Non-admin login
        $response = $this->actingAs($this->nonAdmin)->get(route('admin.program-contents.index'));
        $response->assertStatus(403); // Forbidden atau redirect ke unauthorized
    }

    public function test_admin_can_access_cms_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.program-contents.index'));
        $response->assertStatus(200);
        $response->assertViewHas('contents');
    }

    public function test_admin_can_update_program_content_and_invalidates_cache()
    {
        $content = ProgramContent::create([
            'section' => 'home_hero',
            'key' => 'title',
            'title' => 'Judul Awal',
            'body' => null,
            'icon' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Set Cache agar terisi terlebih dahulu
        Cache::remember("program_contents_home_hero", 600, function () use ($content) {
            return collect([$content]);
        });

        $this->assertTrue(Cache::has("program_contents_home_hero"));

        $response = $this->actingAs($this->admin)->put(route('admin.program-contents.update', $content), [
            'title' => 'Judul Baru dari CMS',
            'body' => '<p>Konten baru yang aman</p>',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.program-contents.index', ['section' => 'home_hero']));
        $this->assertDatabaseHas('program_contents', [
            'id' => $content->id,
            'title' => 'Judul Baru dari CMS',
            'body' => '<p>Konten baru yang aman</p>'
        ]);

        // Verifikasi cache terhapus/di-invalidate
        $this->assertFalse(Cache::has("program_contents_home_hero"));
    }

    public function test_xss_protection_removes_malicious_tags()
    {
        $content = ProgramContent::create([
            'section' => 'home_hero',
            'key' => 'subtitle',
            'title' => null,
            'body' => 'Deskripsi lama',
            'icon' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.program-contents.update', $content), [
            'body' => '<p>Konten <script>alert("XSS")</script> <b>Aman</b></p>',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.program-contents.index', ['section' => 'home_hero']));
        
        $updatedContent = ProgramContent::find($content->id);
        
        // <script> harus dihapus, <b> harus tetap ada sesuai whitelist
        $this->assertStringNotContainsString('<script>', $updatedContent->body);
        $this->assertStringContainsString('<b>Aman</b>', $updatedContent->body);
    }
}
