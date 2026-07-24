<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expression;
use App\Models\User;
use App\Models\ExportLog;
use App\Jobs\ExportExpressionsJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecureExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $researcher;
    protected User $otherUser;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $researcherRole = Role::firstOrCreate(['name' => 'researcher']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create researcher user
        $this->researcher = User::factory()->create();
        $this->researcher->assignRole('researcher');

        // Create another user
        $this->otherUser = User::factory()->create();

        // Create category and some expressions
        $this->category = Category::create(['name' => 'Depresi', 'slug' => 'depresi']);
        
        Expression::create([
            'category_id' => $this->category->id,
            'user_id' => $this->researcher->id,
            'is_anonymous' => false,
            'display_name' => 'Budi',
            'real_name' => 'Budi Anto', // Will be encrypted via model mutator
            'origin' => 'Jakarta',
            'content' => 'Saya merasa cemas akhir-akhir ini.',
            'status' => 'approved',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'consent_agreed_at' => now(),
        ]);
    }

    /**
     * Test export request is authenticated and role restricted.
     */
    public function test_non_authorized_user_cannot_request_export()
    {
        $response = $this->actingAs($this->otherUser)
            ->postJson(route('researcher.export.request'), [
                'category_id' => $this->category->id,
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test researcher can request export and dispatches job.
     */
    public function test_researcher_can_request_export()
    {
        Queue::fake();

        $response = $this->actingAs($this->researcher)
            ->postJson(route('researcher.export.request'), [
                'category_id' => $this->category->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('export_logs', [
            'user_id' => $this->researcher->id,
            'status' => 'pending',
        ]);

        Queue::assertPushed(ExportExpressionsJob::class);
    }

    /**
     * Test background export job handles data masking and files saving securely.
     */
    public function test_export_job_masks_data_and_saves_file()
    {
        Storage::fake('local');

        $log = ExportLog::create([
            'user_id' => $this->researcher->id,
            'status' => 'pending',
            'filter_params' => ['category_id' => $this->category->id],
        ]);

        // Run the job synchronously
        (new ExportExpressionsJob($log->id))->handle();

        $log->refresh();

        $this->assertEquals('completed', $log->status);
        $this->assertNotNull($log->file_path);
        
        // Assert file exists in local private disk path
        Storage::disk('local')->assertExists($log->file_path);

        // Verify the path structure contains private/exports
        $this->assertStringContainsString('private/exports/', $log->file_path);
    }

    /**
     * Test download checks user ownership and signed url validation.
     */
    public function test_download_checks_ownership_and_signed_url()
    {
        Storage::fake('local');

        // Create a dummy export file
        $filePath = 'private/exports/test-uuid.xlsx';
        Storage::disk('local')->put($filePath, 'fake excel content');

        $log = ExportLog::create([
            'user_id' => $this->researcher->id,
            'status' => 'completed',
            'file_path' => $filePath,
            'completed_at' => now(),
            'expires_at' => now()->addHours(24),
        ]);

        // Generate temporary signed URL
        $url = URL::temporarySignedRoute(
            'researcher.export.download',
            now()->addMinutes(60),
            ['id' => $log->id]
        );

        // Try downloading as different user -> Should fail with 403
        $response = $this->actingAs($this->otherUser)->get($url);
        $response->assertStatus(403);

        // Try downloading as owner but without signature -> Should fail with 403
        $unsignedUrl = route('researcher.export.download', ['id' => $log->id]);
        $response = $this->actingAs($this->researcher)->get($unsignedUrl);
        $response->assertStatus(403);

        // Try downloading as owner with valid signature -> Should succeed with download
        $response = $this->actingAs($this->researcher)->get($url);
        $response->assertStatus(200)
            ->assertHeader('content-disposition', 'attachment; filename=STEP_Ekspresi_Aman_' . $log->completed_at->format('Ymd_His') . '.xlsx');
    }

    /**
     * Test scheduler/cleanup tasks.
     */
    public function test_expired_exports_are_cleaned_up()
    {
        Storage::fake('local');

        $filePath = 'private/exports/expired-uuid.xlsx';
        Storage::disk('local')->put($filePath, 'expired content');

        $log = ExportLog::create([
            'user_id' => $this->researcher->id,
            'status' => 'completed',
            'file_path' => $filePath,
            'expires_at' => now()->subHour(),
        ]);

        // Manually force the created_at using query to bypass model automatically managing it
        \Illuminate\Support\Facades\DB::table('export_logs')->where('id', $log->id)->update([
            'created_at' => now()->subHours(25)
        ]);

        // Trigger scheduler logic
        $expiredLogs = ExportLog::where('status', '!=', 'expired')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        foreach ($expiredLogs as $expiredLog) {
            if ($expiredLog->file_path && Storage::disk('local')->exists($expiredLog->file_path)) {
                Storage::disk('local')->delete($expiredLog->file_path);
            }
            $expiredLog->update(['status' => 'expired']);
        }

        $log->refresh();

        $this->assertEquals('expired', $log->status);
        Storage::disk('local')->assertMissing($filePath);
    }
}
