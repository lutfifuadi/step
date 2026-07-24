<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $researcher = Role::firstOrCreate(['name' => 'researcher']);
        $siswa = Role::firstOrCreate(['name' => 'siswa']);

        $permissions = [
            'view expressions',
            'moderate expressions',
            'delete expressions',
            'export data',
            'manage users',
            'manage content',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin->givePermissionTo(Permission::all());

        $researcher->givePermissionTo([
            'view expressions',
            'export data',
            'view dashboard',
        ]);

        $siswa->givePermissionTo(['view expressions']);
    }
}
