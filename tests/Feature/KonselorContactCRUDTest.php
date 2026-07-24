<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\KonselorContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KonselorContactCRUDTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $nonAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'researcher']);

        // Setup Users
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->nonAdmin = User::factory()->create();
    }

    public function test_non_admin_cannot_access_konselor_crud(): void
    {
        $response = $this->actingAs($this->nonAdmin)->get(route('admin.konselor.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_konselor_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.konselor.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.konselor.index');
    }

    public function test_admin_can_create_konselor_contact_with_valid_phone(): void
    {
        $data = [
            'name' => 'Konselor BK Satu',
            'role' => 'Guru BK',
            'institusi' => 'MAN 1 Kota Bandung',
            'phone' => '081234567890',
            'email' => 'satu@man1bandung.sch.id',
            'room' => 'Ruang BK',
            'availability' => 'Senin - Jumat',
            'sort_order' => 1,
            'is_active' => 1
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.konselor.store'), $data);
        $response->assertRedirect(route('admin.konselor.index'));
        
        $this->assertDatabaseHas('konselor_contacts', [
            'name' => 'Konselor BK Satu',
            'phone' => '081234567890',
            'is_active' => 1
        ]);
    }

    public function test_admin_cannot_create_konselor_contact_with_invalid_phone(): void
    {
        $data = [
            'name' => 'Konselor BK Dua',
            'role' => 'Guru BK',
            'institusi' => 'MAN 1 Kota Bandung',
            'phone' => '12345', // Nomor tidak valid
            'email' => 'dua@man1bandung.sch.id',
            'sort_order' => 1,
            'is_active' => 1
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.konselor.store'), $data);
        $response->assertSessionHasErrors(['phone']);
    }

    public function test_admin_can_update_konselor_contact(): void
    {
        $contact = KonselorContact::create([
            'name' => 'Dra. Siti',
            'role' => 'Koordinator BK',
            'institusi' => 'MAN 1 Kota Bandung',
            'phone' => '081299998888',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $updateData = [
            'name' => 'Dra. Hj. Siti Aminah, M.Pd.',
            'role' => 'Koordinator BK',
            'institusi' => 'MAN 1 Kota Bandung',
            'phone' => '081299997777', // Ganti nomor telepon
            'sort_order' => 2,
            'is_active' => 1
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.konselor.update', $contact), $updateData);
        $response->assertRedirect(route('admin.konselor.index'));

        $this->assertDatabaseHas('konselor_contacts', [
            'id' => $contact->id,
            'name' => 'Dra. Hj. Siti Aminah, M.Pd.',
            'phone' => '081299997777',
            'sort_order' => 2
        ]);
    }

    public function test_admin_can_toggle_konselor_contact_status(): void
    {
        $contact = KonselorContact::create([
            'name' => 'Dra. Siti',
            'role' => 'Koordinator BK',
            'institusi' => 'MAN 1 Kota Bandung',
            'phone' => '081299998888',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.konselor.toggle', $contact));
        $response->assertRedirect(route('admin.konselor.index'));

        $this->assertDatabaseHas('konselor_contacts', [
            'id' => $contact->id,
            'is_active' => 0
        ]);
    }

    public function test_admin_can_delete_konselor_contact(): void
    {
        $contact = KonselorContact::create([
            'name' => 'Dra. Siti',
            'role' => 'Koordinator BK',
            'institusi' => 'MAN 1 Kota Bandung',
            'phone' => '081299998888',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.konselor.destroy', $contact));
        $response->assertRedirect(route('admin.konselor.index'));

        $this->assertDatabaseMissing('konselor_contacts', [
            'id' => $contact->id
        ]);
    }

    public function test_public_pages_display_fallback_if_no_active_contacts(): void
    {
        // Pastikan tidak ada kontak aktif
        KonselorContact::query()->delete();

        $response = $this->get(route('ekspresi.create'));
        $response->assertStatus(200);
        $response->assertSee('Into The Light Indonesia');
        $response->assertSee('119 ext 8');
    }

    public function test_public_pages_display_active_contacts(): void
    {
        $contact = KonselorContact::create([
            'name' => 'Dra. Siti Aminah',
            'role' => 'Koordinator BK',
            'institusi' => 'MAN 1 Kota Bandung',
            'phone' => '081299998888',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get(route('ekspresi.create'));
        $response->assertStatus(200);
        $response->assertSee('Dra. Siti Aminah');
        $response->assertSee('081299998888');
    }
}
