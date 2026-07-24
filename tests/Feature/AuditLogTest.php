<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Expression;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
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

    public function test_non_admin_cannot_access_audit_log()
    {
        // Tamu / Guest
        $response = $this->get(route('admin.audit-log.index'));
        $response->assertRedirect('/login');

        // Non-admin login
        $response = $this->actingAs($this->nonAdmin)->get(route('admin.audit-log.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_audit_log_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.audit-log.index'));
        $response->assertStatus(200);
        $response->assertViewHas('activities');
        $response->assertViewHas('admins');
        $response->assertViewHas('events');
        $response->assertViewHas('subjectTypes');
    }

    public function test_admin_can_filter_audit_log_by_admin()
    {
        // Buat admin lain
        $otherAdmin = User::factory()->create();
        $otherAdmin->assignRole('admin');

        // Buat activity log
        activity()->causedBy($this->admin)->log('Aksi Admin Utama');
        activity()->causedBy($otherAdmin)->log('Aksi Admin Lain');

        // Akses index tanpa filter admin
        $response = $this->actingAs($this->admin)->get(route('admin.audit-log.index'));
        $response->assertStatus(200);
        $this->assertCount(2, $response->viewData('activities'));

        // Akses index dengan filter admin
        $responseFiltered = $this->actingAs($this->admin)->get(route('admin.audit-log.index', [
            'causer_id' => $this->admin->id
        ]));
        $responseFiltered->assertStatus(200);
        $this->assertCount(1, $responseFiltered->viewData('activities'));
        $this->assertEquals('Aksi Admin Utama', $responseFiltered->viewData('activities')->first()->description);
    }

    public function test_admin_can_filter_audit_log_by_event()
    {
        // Buat activity log dengan event custom
        activity()->event('custom_event_1')->log('Test Event 1');
        activity()->event('custom_event_2')->log('Test Event 2');

        // Akses index dengan filter event
        $responseFiltered = $this->actingAs($this->admin)->get(route('admin.audit-log.index', [
            'event' => 'custom_event_1'
        ]));
        $responseFiltered->assertStatus(200);
        $this->assertCount(1, $responseFiltered->viewData('activities'));
        $this->assertEquals('Test Event 1', $responseFiltered->viewData('activities')->first()->description);
    }

    public function test_admin_can_filter_audit_log_by_date()
    {
        // Buat log kemarin, hari ini, besok
        $yesterday = now()->subDay();
        $today = now();
        $tomorrow = now()->addDay();

        activity()->createdAt($yesterday)->log('Log Kemarin');
        activity()->createdAt($today)->log('Log Hari Ini');
        activity()->createdAt($tomorrow)->log('Log Besok');

        // Filter dari hari ini
        $response = $this->actingAs($this->admin)->get(route('admin.audit-log.index', [
            'date_start' => $today->toDateString(),
            'date_end' => $tomorrow->toDateString(),
        ]));

        $response->assertStatus(200);
        $activities = $response->viewData('activities');
        
        // Memastikan log kemarin tidak masuk filter
        foreach ($activities as $activity) {
            $this->assertNotEquals('Log Kemarin', $activity->description);
        }
    }
}
